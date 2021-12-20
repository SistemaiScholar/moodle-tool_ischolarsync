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
 * Privacy Subsystem implementation for tool_ischolarsync.
 *
 * @package    tool_ischolarsync
 * @copyright  2021, iScholar - Gestão Escolar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_ischolarsync\privacy;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\approved_contextlist;
use my_userlist_provider;
use tool_ischolarsync\ischolar;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem for tool_ischolarsync implementing null_provider.
 *
 * @copyright  2021, iScholar - Gestão Escolar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        // This plugin stores user data.
        \core_privacy\local\metadata\provider,

        // This plugin contains user's enrolments.
        \core_privacy\local\request\plugin\provider,

        // This plugin is capable of determining which users have data within it.
        my_userlist_provider {

    //
    // Implementing the \core_privacy\local\metadata\provider interface.
    // Describing the type of data stored.
    //

    /**
     * Describing the type of data stored.
     *
     * @param   collection $collection The initialised collection to add items to.
     * @return  collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection) {
        $collection->add_subsystem_link('core_user', [], 'privacy:metadata:core_user');
        $collection->add_subsystem_link('enrol_manual', [], 'privacy:metadata:enrol_manual');
        $collection->add_subsystem_link('core_group', [], 'privacy:metadata:core_group');

        return $collection;
    }

    //
    // Implementing the \core_privacy\local\request\plugin\provider interface.
    // Providing a way to export user data.
    //

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return contextlist $contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) {
        $contextlist = new contextlist();

        $contextlist->add_from_sql('
            SELECT id
            FROM {context}
            WHERE instanceid = :userid AND contextlevel = :contextuser',
            [
                'contextuser'  => CONTEXT_USER,
                'userid'       => $userid
            ]
        );

        $contextlist->add_from_sql('
            SELECT ctx.id
            FROM {groups_members} gm
            JOIN {groups} g ON gm.groupid = g.id
            JOIN {context} ctx ON g.courseid = ctx.instanceid AND ctx.contextlevel = :contextcourse
            WHERE gm.userid = :userid AND gm.component = :component',
            [
                'contextcourse' => CONTEXT_COURSE,
                'userid'        => $userid,
                'component'     => ischolar::PLUGIN_ID
            ]
        );

        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        if (empty($contextlist)) {
            return;
        }

        \core_user\privacy\provider::export_user_data ($contextlist);
        \core_group\privacy\provider::export_user_data ($contextlist);
    }

    /**
     * Delete all use data which matches the specified deletion_criteria.
     *
     * @param context $context A user context.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        if (empty($context)) {
            return;
        }
        if ($context->contextlevel == CONTEXT_USER) {
            \core_user\privacy\provider::delete_user_data($context->instanceid, $context);
        }
        if ($context->contextlevel == CONTEXT_COURSE) {
            // Delete all the associated groups.
            \core_group\privacy\provider::delete_groups_for_all_users($context, ischolar::PLUGIN_ID);
        }
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        if (empty($contextlist->count())) {
            return;
        }
        foreach ($contextlist as $context) {
            // Let's be super certain that we have the right information for this user here.
            if ($context->contextlevel == CONTEXT_USER && $contextlist->get_user()->id == $context->instanceid) {
                \core_user\privacy\provider::delete_user_data($contextlist->get_user()->id, $contextlist->current());
            }
        }

        \core_group\privacy\provider::delete_groups_for_user($contextlist, ischolar::PLUGIN_ID);
    }

    //
    // Implementing the \core_privacy\local\request\core_userlist_provider interface.
    //

    /**
     * Get the list of users who have data within a context.
     *
     * @param   userlist    $userlist   The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if ($context instanceof \context_user) {
            $userlist->add_user($context->instanceid);
        }

        if ($context instanceof \context_course) {
            \core_group\privacy\provider::get_group_members_in_context($userlist, ischolar::PLUGIN_ID);
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param   approved_userlist   $userlist   The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        $context = $userlist->get_context();

        if ($context instanceof \context_user) {
            \core_user\privacy\provider::delete_user_data($context->instanceid, $context);
        }

        if ($context instanceof \context_course) {
            \core_group\privacy\provider::delete_groups_for_users($userlist, ischolar::PLUGIN_ID);
        }
    }
}
