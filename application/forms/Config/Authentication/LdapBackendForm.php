<?php
// {{{ICINGA_LICENSE_HEADER}}}
/**
 * This file is part of Icinga 2 Web.
 * 
 * Icinga 2 Web - Head for multiple monitoring backends.
 * Copyright (C) 2013 Icinga Development Team
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * @copyright 2013 Icinga Development Team <info@icinga.org>
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt GPL, version 2
 * @author    Icinga Development Team <info@icinga.org>
 */
// {{{ICINGA_LICENSE_HEADER}}}

namespace Icinga\Form\Config\Authentication;

use \Icinga\Application\Config as IcingaConfig;
use \Icinga\Application\Icinga;
use \Icinga\Application\Logger;
use \Icinga\Application\DbAdapterFactory;

use \Icinga\Web\Form;
use \Zend_Config;

/**
 * Form for adding or modifying LDAP authentication backends
 *
 */
class LdapBackendForm extends BaseBackendForm
{
    /**
     * Create this form and add all required elements
     *
     * @see Form::create()
     */
    public function create()
    {
        $name = $this->filterName($this->getBackendName());
        $backend = $this->getBackend();

        $this->addElement(
            'text',
            'backend_'.$name.'_name',
            array(
                'required' => true,
                'allowEmpty'    =>  false,
                'label' => 'Backend name',
                'value' => $this->getBackendName()
            )
        );

        $this->addElement(
            'text',
            'backend_' . $name . '_hostname',
            array(
                'label'     => 'LDAP server host',
                'allowEmpty'    =>  false,
                'value'     => $backend->get('hostname', 'localhost'),
                'required'  => true
            )
        );

        $this->addElement(
            'text',
            'backend_' . $name . '_root_dn',
            array(
                'label'     => 'LDAP root dn',
                'value'     => $backend->get('hostname', 'ou=people,dc=icinga,dc=org'),
                'required'  => true
            )
        );

        $this->addElement(
            'text',
            'backend_' . $name . '_bind_dn',
            array(
                'label'     => 'LDAP bind dn',
                'value'     => $backend->get('bind_dn', 'cn=admin,cn=config'),
                'required'  => true
            )
        );

        $this->addElement(
            'password',
            'backend_' . $name . '_bind_pw',
            array(
                'label'     => 'LDAP bind password',
                'renderPassword' => true,
                'value'     => $backend->get('bind_pw', 'admin'),
                'required'  => true
            )
        );

        $this->addElement(
            'text',
            'backend_' . $name . '_bind_user_class',
            array(
                'label'     => 'LDAP user object class',
                'value'     => $backend->get('user_class', 'inetOrgPerson'),
                'required'  => true
            )
        );

        $this->addElement(
            'text',
            'backend_' . $name . '_bind_user_name_attribute',
            array(
                'label'     => 'LDAP user name attribute',
                'value'     => $backend->get('user_name_attribute', 'uid'),
                'required'  => true
            )
        );

        $this->setSubmitLabel('Save backend');
    }

    /**
     * Return the ldap authentication backend configuration for this form
     *
     * @return array
     * @see BaseBackendForm::getConfig
     */
    public function getConfig()
    {
        $name = $this->getBackendName();
        $prefix = 'backend_' . $this->filterName($name) . '_';

        $section = $this->getValue($prefix . 'name');
        $cfg = array(
            'backend'                   =>  'ldap',
            'target'                    =>  'user',
            'hostname'                  =>  $this->getValue($prefix . 'hostname'),
            'root_dn'                   =>  $this->getValue($prefix . 'root_dn'),
            'bind_dn'                   =>  $this->getValue($prefix . 'bind_dn'),
            'bind_pw'                   =>  $this->getValue($prefix . 'bind_pw'),
            'bind_user_class'           =>  $this->getValue($prefix . 'bind_user_class'),
            'bind_user_name_attribute'  =>  $this->getValue($prefix . 'bind_user_name_attribute')
        );
        return array(
            $section => $cfg
        );
    }
}
