<?php

/**
 * This file is part of a diversworld Contao Bundle.
 *
 * (c) Eckhard becker 2020 <info@diversworld.eu>
 * @author     Eckhard becker
 * @package    Course Manager
 * @license    GPL-3.0-or-later
 * @see        https://github.com/diversworld/contao-coursemanager-bundle
 *
 */

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['course_manager'] = [
    'course_planner' => [
        'tables' => ['tl_courses'],
        'icon' => '../assets/icon.png'
    ],
    ['course_dates'] => [
        'tables' => ['tl_coursedates'],
        'icon' => '../assets/icon.png'
    ]
];

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_courses'] = \Diversworld\ContaoCoursemanagerBundle\Model\CoursesModel::class;
