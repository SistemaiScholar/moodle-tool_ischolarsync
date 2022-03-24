<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Provide functions to integrate with an iScholar System.
 *
 * @package   tool_ischolarsync
 * @copyright 2021, iScholar - Gestão Escolar
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_ischolarsync;

defined('MOODLE_INTERNAL') || die();

/**
 * Class to config the plugin.
 *
 * @package    tool_ischolarsync
 * @copyright  2021, iScholar - Gestão Escolar
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ischolar {
    /** Plugin id. */
    const PLUGIN_ID         = 'tool_ischolarsync';
    /** Service name. */
    const SERVICE_NAME      = 'iScholar Synchronization';
    /** Service id. */
    const SERVICE_ID        = 'ischolarsync';
    /** Settings page. */
    const SETTINGS_PAGE     = 'settingsischolarsync';

    /** Functions list. */
    const SERVICE_FUNCTIONS = [
        'core_course_get_categories',           // Mdl v2.3 Return category details (Also used on ischolar::ping).
        'core_user_create_users',               // Mdl v2.0 Create users.
        'core_user_update_users',               // Mdl v2.0 Update users.
        'core_user_get_users_by_field',         // Mdl v2.5 Retrieve users' information for a specified unique field.
        // 'core_user_get_users',                  // Mdl v2.5 Search for users matching the parameters.
        // 'core_course_create_courses',           // Mdl v2.0 Create new courses.
        // 'core_course_update_courses',           // Mdl v2.5 Update courses.
        'core_course_get_courses',              // Mdl v2.0 Return course details.
        'enrol_manual_enrol_users',             // Mdl v2.0 Manual enrol users.
        // 'enrol_manual_unenrol_users',           // Mdl v3.0 Manual unenrol users.
        'core_enrol_get_enrolled_users',        // Mdl v2.1 Get enrolled users by course id.
        // 'core_enrol_search_users',              // Mdl v3.8 Search within the list of course participants.
        'core_enrol_get_users_courses',         // Mdl v2.0 Get the list of courses where a user is enrolled in.
        'core_group_create_groups',             // Creates new groups.
        'core_group_get_course_user_groups',    // Returns all groups in specified course for the specified user.
        'core_group_add_group_members',         // Adds group members.
        'core_group_delete_group_members',      // Deletes group members.
        'core_group_get_course_groups',         // Returns all groups in specified course.
        // 'core_group_delete_groups',             // Deletes all specified groups.
        'core_user_view_user_profile',          // Simulates the web-interface view of user/view.php and user/profile.php.
    ];

    /** Customfields list. */
    const USER_CUSTOMFIELDS = [
        'ischolar_aluno',
        'ischolar_professor'
    ];


    /**
     * Get the plugin configuration parameters.
     *
     * @return object (a collection of settings parameters/values).
     */
    public static function getsettings() {
        $config = get_config(self::PLUGIN_ID);

        return $config;
    }


    /**
     * Performs the configuration in the plugin and in the iScholar system.
     *
     * @return bool true if configuration is ok, false if something fails.
     */
    public static function setintegration() {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/user/externallib.php');
        require_once($CFG->dirroot . '/user/profile/definelib.php');
        require_once($CFG->dirroot . '/user/profile/lib.php');

        // Following the steps described in 'Dashboard / Site administration / Server / Web services / Overview'.
        try {
            //
            // 1. Activating webservice.
            //
            set_config('enablewebservices', 1);

            //
            // 2. Enabling REST protocol.
            //
            if (!isset($CFG->webserviceprotocols) || $CFG->webserviceprotocols == '') {
                set_config('webserviceprotocols', 'rest');
            } else {
                $services = explode(',', $CFG->webserviceprotocols);
                if (array_search('rest', $services) === false) {
                    $services[] = 'rest';
                    set_config('webserviceprotocols', implode(',', $services));
                }
            }

            //
            // 3. Creating specific user (ischolar).
            //

            // Search user ischolar.
            $user = \core_user_external::get_users_by_field('username', ['ischolar']);
            $user = \external_api::clean_returnvalue(\core_user_external::get_users_by_field_returns(), $user);

            $payload = [
                'plugin'  => self::PLUGIN_ID,
                'usuario' => 'ischolar'
            ];
            $response = self::callischolar("gerador_senha", $payload);
            if (isset($response['status']) && $response['status'] == 'sucesso') {
                $password = $response['dados'];
            } else {
                throw new \Exception("passgenerror");
            }
self::debugbox($password, 'password');
            // If ischolar user does not exist, it will be created.
            if (count($user) == 0) {
                $user1 = array(
                    'username'    => 'ischolar',
                    'password'    => $password,
                    'idnumber'    => 'ischolar',
                    'firstname'   => 'iScholar',
                    'lastname'    => get_string('settings:userlastname', self::PLUGIN_ID),
                    'email'       => 'suporte@ischolar.com.br',
                    'description' => get_string('settings:userdescription', self::PLUGIN_ID),
                );
                if ($CFG->version >= 2018120300) {   // If moodle version is 3.6 or later.
                    $user1['maildisplay'] = 0;
                }
                $user = \core_user_external::create_users([$user1]);
                $user = \external_api::clean_returnvalue(\core_user_external::create_users_returns(), $user);

                // Change user (moodle does not allow creating webservice users, but allows changing the user to webservice).
                $user1['id']   = $user[0]['id'];
                $user1['auth'] = 'webservice';
                $user          = \core_user_external::update_users([$user1]);
            } else {    // If user already exists, it is reset.
                $ischolaruser = \core_user_external::get_users_by_field('username', ['ischolar']);
                $user1 = array(
                    'id'          => $ischolaruser[0]['id'],
                    'auth'        => 'webservice',
                    'username'    => 'ischolar',
                    'password'    => $password,
                    'idnumber'    => 'ischolar',
                    'firstname'   => 'iScholar',
                    'lastname'    => get_string('settings:userlastname', self::PLUGIN_ID),
                    'email'       => 'suporte@ischolar.com.br',
                    'description' => get_string('settings:userdescription', self::PLUGIN_ID),
                );
                if ($CFG->version >= 2018120300) {   // If moodle version is 3.6 or later.
                    $user1['maildisplay'] = '0';
                }
                \core_user_external::update_users([$user1]);
            }

            //
            // 4. Checking user Capabilities.
            // Puts ischolar user in the admins group.
            //
            $potentialadmisselector = new \core_role_admins_potential_selector();
            $ischolar               = $potentialadmisselector->find_users('iScholar');
            $ischolar               = current($ischolar);
            if ($ischolar != false) {
                $ischolar   = current($ischolar);
                $idischolar = $ischolar->id;

                $admins = array();
                foreach (explode(',', $CFG->siteadmins) as $admin) {
                    $admin = (int)$admin;
                    if ($admin) {
                        $admins[$admin] = $admin;
                    }
                }
                $logstringold        = implode(', ', $admins);      // Log before.
                $admins[$idischolar] = $idischolar;                 // Changing.
                $logstringnew        = implode(', ', $admins);      // Log after.

                set_config('siteadmins', implode(',', $admins));
                add_to_config_log('siteadmins', $logstringold, $logstringnew, 'core');
            }

            //
            // 5. Selecting a service.
            //
            require_once($CFG->dirroot . '/webservice/lib.php');
            $wsman      = new \webservice;
            $service    = $wsman->get_external_service_by_shortname(self::SERVICE_ID);
            if ($service == false) {                                        // Create service if it does not exist.
                $serviceid  = $wsman->add_external_service((object)[
                    'name'               => self::SERVICE_NAME,
                    'shortname'          => self::SERVICE_ID,
                    'enabled'            => 1,
                    'requiredcapability' => '',
                    'restrictedusers'    => true,
                    'component'          => null,
                    'downloadfiles'      => true,
                    'uploadfiles'        => true,
                ]);
            } else {                                                          // If service already exists, reset parameters.
                $serviceid = $service->id;
                $wsman->update_external_service((object)[
                    'id'                 => $serviceid,
                    'name'               => self::SERVICE_NAME,
                    'shortname'          => self::SERVICE_ID,
                    'enabled'            => 1,
                    'requiredcapability' => '',
                    'restrictedusers'    => true,
                    'component'          => null,
                    'downloadfiles'      => true,
                    'uploadfiles'        => true,
                ]);
            }

            //
            // 6. Add functions that the user can perform.
            //

            // Searching for functions and clearing current roles.
            $externalfunctions = $wsman->get_external_functions([$serviceid]);
            foreach ($externalfunctions as $function) {
                $wsman->remove_external_function_from_service($function->name, $serviceid);
            }
            // Adding necessary functions.
            foreach (self::SERVICE_FUNCTIONS as $function) {
                $wsman->add_external_function_to_service($function, $serviceid);
            }

            //
            // 7. Add ischolar user as authorized user.
            //

            // Checking if user is already authorized.
            $authusers  = $wsman->get_ws_authorised_users($serviceid);
            $found      = false;
            foreach ($authusers as $user) {
                if ($user->firstname == 'iScholar') {
                    $found = true;
                } else {     // Disallow users other than iScholar.
                    $wsman->remove_ws_authorised_user($user, $serviceid);
                }
            }
            // If not, authorize.
            if ($found == false) {
                $ischolaruser = \core_user_external::get_users_by_field('username', ['ischolar']);
                $serviceuser = new \stdClass();
                $serviceuser->externalserviceid = $serviceid;
                $serviceuser->userid = $ischolaruser[0]['id'];
                $wsman->add_ws_authorised_user($serviceuser);
            }

            //
            // 8. Creates a service token for the user.
            //
            $ischolaruser = \core_user_external::get_users_by_field('username', ['ischolar']);
            $tokens       = $wsman->get_user_ws_tokens($ischolaruser[0]['id']);
            $found        = false;

            foreach ($tokens as $token) {           // Looking for a token.
                if ($token->name == 'iScholar Synchronization') {
                    if ($token->enabled != '1') {   // Invalid token is removed.
                        delete_user_ws_token($token->id);
                    } else {
                        $found       = true;
                        $tokenmoodle = $token->token;
                    }
                }
            }

            if ($found == false) {                  // If token does not exist, it will be created.
                $tokenmoodle = external_generate_token(
                    EXTERNAL_TOKEN_PERMANENT,
                    $serviceid,
                    $ischolaruser[0]['id'],
                    \context_system::instance()
                );
            }

            //
            // 9. Activating Web services documentation (developer documentation).
            //
            set_config('enablewsdocumentation', 1);

            //
            // 10. Test the service.
            //
            $payload = [
                'token_moodle' => $tokenmoodle,
                'url_moodle'   => $CFG->wwwroot
            ];
            $response = self::callischolar("configura_moodle_sync", $payload);

            //
            // 12. Custom fields.
            //

            // User custom fields.
            $categories = $DB->get_records('user_info_category', ['name' => 'iScholar']);
            if (count($categories) == 0) {
                // Create iScholar category for custom fields.
                $data            = new \stdClass();
                $data->name      = 'iScholar';
                $data->sortorder = (int) $DB->get_field_sql('SELECT MAX(sortorder) FROM {user_info_category}') + 1;

                if ((int) $CFG->version < 2021051700) {     // If moodle version is below 3.11.
                    $DB->insert_record ('user_info_category', $data, false, false);
                } else {
                    \profile_save_category($data);
                }
            }
            $idcategory = $DB->get_record('user_info_category', ['name' => 'iScholar']);
            $idcategory = $idcategory->id;

            foreach (self::USER_CUSTOMFIELDS as $customfield) {
                $field = $DB->get_records('user_info_field', ['shortname' => $customfield]);

                if (empty($field)) {
                    $data                    = new \stdClass();
                    $data->shortname         = $customfield;
                    $data->name              = get_string('customfield:'.$customfield, self::PLUGIN_ID);
                    $data->datatype          = 'text';
                    $data->description       = get_string('customfield:'.$customfield, self::PLUGIN_ID);
                    $data->categoryid        = $idcategory;
                    $data->required          = false;
                    $data->locked            = true;
                    $data->visible           = '3';
                    $data->forceunique       = false;
                    $data->signup            = false;
                    $data->param1            = 30;
                    $data->param2            = 2048;

                    $field = new \profile_define_base();
                    $field->define_save($data);
                }
            }

        } catch (\Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * Disable the plugin on Moodle and the integration on the iScholar system.
     *
     * @return array A array indicating the status and some error message if any.
     */
    public static function unsetintegration() {
        global $CFG;

        try {
            //
            // Disabling iScholar integration.
            //

            $response = self::callischolar("desativa_moodle_sync");

            if (isset($result['status']) && $result['status'] == 'sucesso') {
                $result['status'] = true;
            } else {
                $result['status'] = false;
            }
        } catch (\Exception $e) {
            $result = [
                'status' => false,
                'msg'    => $e->getMessage()
            ];
        }

        return $result;
    }


    /**
     * Check plugin configuration status.
     *
     * @return string html code listing the configuration status itens.
     */
    public static function healthcheck() {
        global $CFG, $OUTPUT, $DB, $USER;
        require_once($CFG->dirroot . '/user/externallib.php');
        require_once($CFG->dirroot . '/webservice/lib.php');

        $config         = self::getsettings();
        $ischolaruserid = null;
        $serviceid      = null;
        $tokenmoodle    = null;

        try {
            //
            // 0. Plugin activation.
            //
            $results[0]['desc'] = 'pluginenabled';
            if (isset($config->enabled) && $config->enabled == '1') {
                $results[0]['status'] = true;
            } else {
                $results[0]['status'] = false;
            }

            //
            // 1. Webservice activation.
            //
            $results[1]['desc'] = 'webservice';
            if ($CFG->enablewebservices == 1) {
                $results[1]['status'] = true;
            } else {
                $results[1]['status'] = false;
            }

            //
            // 2. REST protocol activation.
            //
            $results[2]['desc'] = 'webserviceprotocols';
            $protocols = (isset($CFG->webserviceprotocols)) ? explode(',', $CFG->webserviceprotocols) : [];
            if (array_search('rest', $protocols) !== false) {
                $results[2]['status'] = true;
            } else {
                $results[2]['status'] = false;
            }

            //
            // 3. Plugin specific user (ischolar).
            //
            $results[3]['desc'] = 'createuser';
            $user = \core_user_external::get_users_by_field('username', ['ischolar']);
            $user = \external_api::clean_returnvalue(\core_user_external::get_users_by_field_returns(), $user);
            if (count($user) > 0) {
                $results[3]['status'] = true;
                $ischolaruserid       = $user[0]['id'];
            } else {
                $results[3]['status'] = false;
            }

            //
            // 4. User capabilities (verifies user is admin).
            //
            $results[4]['desc'] = 'usercapability';
            $admins = explode(',', $CFG->siteadmins);
            if ($ischolaruserid !== null && array_search($ischolaruserid, $admins) !== false) {
                $results[4]['status'] = true;
            } else {
                $results[4]['status'] = false;
            }

            //
            // 5. Service.
            //
            $results[5]['desc'] = 'selectservice';
            $wsman = new \webservice;
            $service = $wsman->get_external_service_by_shortname(self::SERVICE_ID);
            if ($service !== false) {
                $results[5]['status'] = true;
                $serviceid = $service->id;
            } else {
                $results[5]['status'] = false;
            }

            //
            // 6. Functions that the user can perform.
            //
            $results[6]['desc'] = 'servicefunctions';
            if ($serviceid !== null) {
                $results[6]['status'] = true;

                $externalfunctions     = $wsman->get_external_functions([$serviceid]);
                $externalfunctionnames = [];
                foreach ($externalfunctions as $function) {
                    $externalfunctionnames[] = $function->name;
                }

                $results[6]['status'] = true;
                foreach (self::SERVICE_FUNCTIONS as $function) {
                    if (in_array($function, $externalfunctionnames) == false) {
                        $results[6]['status'] = false;
                        break;
                    }
                }
            } else {
                $results[6]['status'] = false;
            }

            //
            // 7. iScholar user authorization.
            //
            $results[7]['desc'] = 'serviceuser';
            $authusers = $wsman->get_ws_authorised_users($serviceid);
            $results[7]['status'] = false;
            foreach ($authusers as $user) {
                if ($user->firstname == 'iScholar') {
                    $results[7]['status'] = true;
                    break;
                }
            }

            //
            // 8. Service token for iScholar user.
            //
            $results[8]['desc']     = 'createtoken';
            $tokens                 = $wsman->get_user_ws_tokens($ischolaruserid);
            $results[8]['status']   = false;
            $tokenmoodle            = '';
            foreach ($tokens as $token) {
                if ($token->name == 'iScholar Synchronization' && $token->enabled == '1') {
                    $results[8]['status'] = true;
                    $tokenmoodle = $token->token;
                    break;
                }
            }

            //
            // 9. Web services documentation (developer documentation).
            //
            $results[9]['desc'] = 'webservicedocs';
            if ($CFG->enablewsdocumentation == 1) {
                $results[9]['status'] = true;
            } else {
                $results[9]['status'] = false;
            }

            //
            // 10. Test the service.
            //
            $payload = [
                'token_moodle' => $tokenmoodle,
                'url_moodle'   => $CFG->wwwroot,
            ];
            $response = self::callischolar("verifica_moodle_sync", $payload);

            $results[10]['desc'] = 'servicetest';
            if (isset($response['status']) && $response['status'] == 'sucesso') {
                $results[10]['status'] = true;
            } else {
                $results[10]['status'] = false;
                $results[10]['msg']    = (isset($response['msg'])) ?
                                        $response['msg'] :
                                        get_string('config:servicetestfail', self::PLUGIN_ID);
            }

            //
            // 11. Timezone.
            //
            $results[11]['desc'] = 'timezone';

            // Getting the default moodle timezone.
            $moodletz0  = $CFG->timezone;

            // Getting the user timezone (99 Timezone is the same of default timezone).
            $moodletz1  = ($USER->timezone == '99') ?
                            $moodletz0 : $USER->timezone;

            // Getting the ischolar timezone.
            $ischolartz = isset($response['dados']['time_zone']) ?
                            $response['dados']['time_zone'] : '';

            if ($moodletz0 == $moodletz1 && $moodletz0 == $ischolartz) {
                $results[11]['status'] = true;
            } else {
                $results[11]['status'] = false;
                $results[11]['msg']    = 'timezone';
            }

            //
            // 12. Custom fields
            //
            $results[12]['desc']   = 'customfields';
            $results[12]['status'] = true;

            // User custom fields.
            $categories = $DB->get_records('user_info_category', ['name' => 'iScholar']);
            if (count($categories) == 0) {
                $results[12]['status'] = false;
            } else {
                foreach (self::USER_CUSTOMFIELDS as $customfield) {
                    $field = $DB->get_records('user_info_field', ['shortname' => $customfield]);
                    if (count($field) == 0) {
                        $results[12]['status'] = false;
                        break;
                    }
                }
            }

        } catch (\Exception $e) {
            $result[] = [
                'desc'   => 'exception',
                'status' => $e->getMessage()
            ];
        }

        //
        // Displaying results in html.
        //
        $config = self::getsettings();
        if (isset($config->enabled)) {
            $healthyplugin  = 1;

            if ($config->enabled == '1') {
                if ($CFG->version < 2016120500) {   // If moodle version is older than 3.2.
                    $html  = '<div style="background-color:#eeeeee; border:solid 1px #8f959e; padding:8px; width:530px;">';
                } else {
                    $html  = '<div style="background-color:#eeeeee; border:solid 1px #8f959e; padding:8px;">';
                }
                foreach ($results as $i => $result) {
                    $html .= '<p style="display:flex; flex-direction:row; '.
                                'justify-content:space-between; align-items:center; color:#333;">';
                    $html .= '<span>'.get_string('config:'.$result['desc'], self::PLUGIN_ID).'</span>';
                    if ($CFG->version < 2017051500) {   // If Moodle version is older than 3.3.
                        $html .= ($result['status']) ?
                            '<img style="width:20px; height:20px; margin:0px 10px;" src="'.
                                $CFG->wwwroot.'/admin/tool/ischolarsync/pix/yes.png" />' :
                            '<img style="width:22px; height:22px; margin:0px 10px;" src="'.
                                $CFG->wwwroot.'/admin/tool/ischolarsync/pix/no.png" />';
                    } else {
                        $html .= ($result['status']) ?
                            '<img style="width:20px; height:20px; margin:0px 10px;" src="'.
                                $OUTPUT->image_url('yes', self::PLUGIN_ID).'" />' :
                            '<img style="width:22px; height:22px; margin:0px 10px;" src="'.
                                $OUTPUT->image_url('no', self::PLUGIN_ID).'" />';
                    }
                    $html .= '</p>';

                    if (isset($result['msg'])) {
                        $errordesc    = (get_string_manager()->string_exists('configerror:'.$result['msg'], self::PLUGIN_ID)) ?
                                        get_string('configerror:'.$result['msg'], self::PLUGIN_ID) :
                                        get_string('configerror:general', self::PLUGIN_ID).' '.$result['msg'];
                        $html .= '<p style="color:#882020; margin-left:35px; margin-top:-16px;">';
                        $html .= '<span>'.$errordesc.'<span>';
                        $html .= '</p>';
                    }

                    if ($result['status'] == false && $i != 10 && $i != 11) {  // Ignore checks that the fix button doesn't resolve.
                        $healthyplugin = 0;
                    }
                }

                if ($healthyplugin == 0) {
                    $html .= '<p style="text-align:right; display:block; margin:30px 0px 0px 0px;">
                                <a href="'.$_SERVER['SCRIPT_NAME'].'?section='.self::SETTINGS_PAGE.'&fix=1"
                                    class="btn btn-secondary" type="button">'.
                                    get_string('configerror:fixbutton', self::PLUGIN_ID).
                                    '</a></p>';
                }

                $html .= '</div>';
            } else {
                $healthyplugin = 0;
                $html          = '<div><p style="color:#882020;">'.
                                 get_string('config:plugindisabled', self::PLUGIN_ID).
                                 '</p></div>';
                $result        = self::unsetintegration();
            }

            set_config('healthyplugin', $healthyplugin, self::PLUGIN_ID);

            return $html;
        }

        return '';
    }


    /**
     * Make a call to a iScholar system.
     *
     * @param string $endpoint Endpoint to a iScholar V2 API.
     * @param string $payload Data to send.
     *
     * @return array A array containing the status and error messages if any.
     */
    public static function callischolar($endpoint='', $payload='') {
        try {
            $settings = self::getsettings();

            $headers = ["Content-Type: application/json"];
            if (isset($settings->tokenischolar)) {
                $headers[] = "X-Autorizacao: " . $settings->tokenischolar;
            }
            if (isset($settings->schoolcode)) {
                $headers[] = "X-Codigo-Escola: ". $settings->schoolcode;
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => "https://api.ischolar.app/integracoes/". $endpoint,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => json_encode($payload),
            ));

            $response = json_decode(curl_exec($curl), true);

            curl_close($curl);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        return $response;
    }


    /**
     * A small tool for debug.
     *
     * @param mixed $debug Some vabiable or content.
     * @param string $title A description for the variable.
     */
    public static function debugbox($debug, $title = null) {
        $debug = var_export($debug, true);
        $title = ($title !== null) ?
            "<p style='color:white; background:#333333; margin:0px; padding:5px;'><strong>{$title}</strong></p>" :
            '';
        echo "<div id='debugbox' style='width:100%; margin-top:60px; background:lightgray; border:solid 1px black;'>
            {$title}
            <pre style='margin:7px;'>{$debug}</pre>
        </div>";
    }


    /* *
     *
     *
     * @param int $areaid
     * @param string $contenthash
     * @return bool
     * @throws \dml_exception
     */
}
