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
use \core_privacy\local\request\userlist;

defined('MOODLE_INTERNAL') || die();

if (interface_exists('\core_privacy\local\request\userlist')) {
    interface my_userlist_provider extends \core_privacy\local\request\userlist {
    }
} else {                                    // For older versions of moodle.
    interface my_userlist_provider {
    }
}
