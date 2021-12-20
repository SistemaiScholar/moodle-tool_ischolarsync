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
 * Handler for custom fields
 *
 * @package   tool_ischolarsync
 * @copyright 2021, iScholar - Gestão Escolar
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_ischolarsync\customfield;

defined('MOODLE_INTERNAL') || die;

use core_customfield\api;
use core_customfield\field_controller;

/**
 * id_professor on iScholar system
 *
 * @package   tool_ischolarsync
 * @copyright 2021, iScholar - Gestão Escolar
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class idprofessor_handler extends \core_customfield\handler {
    /** @var int id code of a theacher. */
    static protected $singleton;

    /**
     * Returns a singleton
     *
     * @param int $itemid
     * @return \core_course\customfield\course_handler
     */
    public static function create(int $itemid = 0) {
        if (static::$singleton === null) {
            self::$singleton = new static(0);
        }
        return self::$singleton;
    }

    /**
     * The current user can configure custom fields on this component.
     *
     * @return bool true if the current user can configure custom fields, false otherwise.
     */
    public function can_configure() {
        return false;
    }

    /**
     * The current user can edit given custom fields on the given instance.
     * Called to filter list of fields displayed on the instance edit form.
     * Capability to edit/create instance is checked separately.

     * @param field_controller $field
     * @param int $instanceid id of the instance or 0 if the instance is being created.
     * @return bool true if the current user can edit custom fields, false otherwise.
     */
    public function can_edit(field_controller $field, int $instanceid=0) {
        return false;
    }

    /**
     * The current user can view the value of the custom field for a given custom field and instance.
     * Called to filter list of fields returned by methods get_instance_data(), get_instances_data(),
     *   export_instance_data(), export_instance_data_object().
     * Access to the instance itself is checked by handler before calling these methods
     *
     * @param field_controller $field
     * @param int $instanceid
     * @return bool true if the current user can edit custom fields, false otherwise
     */
    public function can_view(field_controller $field, int $instanceid) {
        return false;
    }
}
