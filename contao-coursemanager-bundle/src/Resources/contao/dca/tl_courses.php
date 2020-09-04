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
 * Table tl_courses
 */
$GLOBALS['TL_DCA']['tl_courses'] = [

    // Config
    'config'      => [
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id' => 'primary'
            ]
        ],
    ],
    'edit'        => [
        ]
    ],
    'list'        => [
        'sorting'           => [
            'mode'        => 2,
            'fields'      => ['title'],
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ],
        'label'             => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_courses']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ],
            'copy'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_courses']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_courses']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show'   => [
                'label'      => &$GLOBALS['TL_LANG']['tl_courses']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ],
        ]
    ],
    // Palettes
    'palettes'    => [
        '__selector__' => ['addSubpalette'],
        'default'      => '{first_legend},title,alias,info,description,price;{second_legend},published,start,stop'
    ],
    // Subpalettes
    'subpalettes' => [
        'addSubpalette' => 'textareaField',
    ],
    // Fields
    'fields'      => [
        'id'             => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'pid' => [
            'search'        => true,
            'foreignKey'    => 'tl_page.title',
            'sql'           => "int(10) unsigned NOT NULL default 0",
            'relation'      => ['type'=>'belongsTo', 'load'=>'lazy']
        ],
        'tstamp'         => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'title'          => [
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'alias'             => [
            'exclude'       => true,
            'inputType'     => 'text',
            'search'        => true,
            'filter'        => true,
            'eval'          => ['rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'],
            'save_callback' => ['tl_courseplan', 'generateAlias'],
            'sql'           => "varchar(255) BINARY NOT NULL default ''"
        ],
        'info'              => [
            'label'         => &$GLOBALS['TL_LANG']['tl_course']['info'],
            'exclude'       => true,
            'inputType'     => 'pageTree',
            'eval'          => ['fieldType'=>'radio'],
            'sql'           => "int(10) unsigned NOT NULL default '0'"
        ],
        'description'       => [
            'exclude'       => true,
            'inputType'     => 'textarea',
            'search'        => true,
            'eval'          => ['rte'=>'tinyMCE', 'tl_class'=>'clr'],
            'sql'           => "text NULL"
        ],
        'price'         => [
            'exclude'       => true,
            'inputType'     => 'text',
            'search'        => true,
            'eval'          => ['tl_class'=>'w50 wizard'],
            'sql'           => "varchar(10) NOT NULL default ''"
        ],
        'published'         => [
            'exclude'       => true,
            'search'        => true,
            'filter'        => true,
            'inputType'     => 'checkbox',
            'eval'          => ['doNotCopy'=>true],
            'sql'           => "char(1) NOT NULL default ''"
        ],
        'start'             => [
            'exclude'       => true,
            'inputType'     => 'text',
            'eval'          => ['rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'sql'           => "varchar(10) NOT NULL default ''"
        ],
        'stop' => [
            'exclude'       => true,
            'inputType'     => 'text',
            'eval'          => ['rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'sql'           => "varchar(10) NOT NULL default ''"
        ]
    ]
];

/**
 * Class tl_courses
 */
class tl_courses extends Contao\Backend
{

    /**
     * Auto-generate an article alias if it has not been set yet
     *
     * @param mixed                $varValue
     * @param Contao\DataContainer $dc
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAlias($varValue, Contao\DataContainer $dc)
    {
        $aliasExists = function (string $alias) use ($dc): bool
        {
            if (in_array($alias, array('top', 'wrapper', 'header', 'container', 'main', 'left', 'right', 'footer'), true))
            {
                return true;
            }

            return $this->Database->prepare("SELECT id FROM tl_courses WHERE alias=? AND id!=?")->execute($alias, $dc->id)->numRows > 0;
        };

        // Generate an alias if there is none
        if ($varValue == '')
        {
            $varValue = Contao\System::getContainer()->get('contao.slug')->generate($dc->activeRecord->title, $dc->activeRecord->pid, $aliasExists);
        }
        elseif ($aliasExists($varValue))
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
    }

}
