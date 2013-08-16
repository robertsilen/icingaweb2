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
 * Base form for authentication backend forms
 *
 */
abstract class BaseBackendForm extends Form
{
    /**
     * The name of the backend currently displayed in this form
     *
     * Will be the section in the authentication.ini file
     *
     * @var string
     */
    private $backendName = "";

    /**
     * The backend configuration as a Zend_Config object
     *
     * @var Zend_Config
     */
    private $backend = null;

    /**
     * The resources to use instead of the factory provided ones (use for testing)
     *
     * @var Zend_Config
     */
    private $resources = null;

    /**
     * Set the name of the currently displayed backend
     *
     * @param string $name      The name to be stored as the section when persisting
     */
    public function setBackendName($name)
    {
        $this->backendName = $name;
    }

    /**
     * Return the backend name of this form
     *
     * @return string
     */
    public function getBackendName()
    {
        return $this->backendName;
    }

    /**
     * Return the backend configuration or a empty Zend_Config object if none is given
     *
     * @return Zend_Config
     */
    public function getBackend()
    {
        return ($this->backend !== null) ? $this->backend : new Zend_Config(array());
    }

    /**
     * Set the backend configuration for initial population
     *
     * @param Zend_Config $backend      The backend to display in this form
     */
    public function setBackend(Zend_Config $backend)
    {
        $this->backend = $backend;
    }

    /**
     * Set an alternative array of resources that should be used instead of the DBFactory resource set
     * (used for testing)
     *
     * @param array $resources              The resources to use for populating the db selection field
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * Return content of the resources.ini or previously set resources for displaying in the database selection field
     *
     * @return array
     */
    public function getResources()
    {
        if ($this->resources === null) {
            return DbAdapterFactory::getResources();
        } else {
            return $this->resources;
        }
    }

    /**
     * Return an array containing all sections defined by this form as the key and all settings
     * as an keyvalue subarray
     *
     * @return array
     */
    abstract public function getConfig();
}
