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
 * Language file for tool_ischolarsync, en-US
 *
 * File         tool_ischolarsync.php
 * Encoding     UTF-8
 *
 * @package   tool_ischolarsync
 * @category  admin tools
 * @copyright 2021, iScholar - Gestão Escolar
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'iScholar <> Moodle Synchronization';

$string['ischolarsettings'] = 'iScholar <> Moodle Synchronization';

$string['settings:enabled'] = 'Enabled';
$string['settings:enabledinfo'] = 'Enable / disable the this plugin.';
$string['settings:tokenischolar'] = 'Token from iScholar:';
$string['settings:tokenischolarinfo'] = 'Access token from your iScholar system. Click <a href="https://ischolar.com.br" target="_blank">here</a> to know more.';
$string['settings:healthcheck'] = 'Configuration status:';
$string['settings:initialsetupinfo'] = '';
$string['settings:userlastname'] = 'Integrations';
$string['settings:userdescription'] = '<h3 dir="ltr" style="text-align: left;"><strong>DO NOT MODIFY OR DELETE THIS USER!</strong></h3><p dir="ltr" style="text-align: left;">Changing or removing this user will cause <em>iScholar</em> plugins to malfunction.</p>';

$string['config:pluginenabled'] = 'Plugin activation.';
$string['config:webservice'] = 'Moodle permission to connect to external systems.';
$string['config:webserviceprotocols'] = 'Communication protocol.';
$string['config:createuser'] = 'Integration user.';
$string['config:usercapability'] = 'Integration user permissions.';
$string['config:selectservice'] = 'iScholar service.';
$string['config:servicefunctions'] = 'iScholar service functions.';
$string['config:serviceuser'] = 'Service user.';
$string['config:createtoken'] = 'Moodle access token.';
$string['config:webservicedocs'] = 'Documentation setup in Moodle.';
$string['config:servicetest'] = 'Configuration in your iScholar system.';
$string['config:servicetestfail'] = 'Communication failure to iScholar.';
$string['config:timezone'] = 'Timezones.';
$string['config:manageauth'] = 'Authentication method activation.';
$string['config:customfields'] = 'Custom fields.';
$string['config:plugindisabled'] = 'Plugin disabled.';
$string['config:exception'] = 'Exception.';

$string['configerror:general'] = 'Communication failure to iScholar:';
$string['configerror:communication'] = 'The iScholar server was unable to communicate with Moodle.';
$string['configerror:timezone'] = 'Check and adjust the time zone settings as described in this <a target="_blank" href="https://ischolar.com.br">help page</a>.';
$string['configerror:tokeninvalido'] = 'The iScholar Token provided is invalid for this application.';
$string['configerror:tokenexpirado'] = 'The iScholar Token provided has expired. Access your iScholar to generate a new token.';
$string['configerror:escoladesconhecida'] = 'The school associated with the iScholar Token is unknown.';
$string['configerror:integracaodesconhecida'] = 'The integration associated with the provided iScholar Token is unknown.';
$string['configerror:tokennaoencontrado'] = 'iScholar token not found.';
$string['configerror:fixbutton'] = 'Fix configuration';

$string['customfield:ischolar_aluno'] = 'Student code';
$string['customfield:ischolar_professor'] = 'Teacher code';
$string['customfield:ischolar_disciplina'] = 'Discipline code';
$string['customfield:ischolar_curso'] = 'Course code';
$string['customfield:ischolar_modalidade'] = 'Modality code';