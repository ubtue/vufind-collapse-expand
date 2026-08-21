<?php

/*
 * Copyright 2021 (C) Bibliotheksservice-Zentrum Baden-
 * Württemberg, Konstanz, Germany
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
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

namespace VuFindCollapseExpand\Search\Solr;

use VuFindSearch\ParamBag;

/**
 * This trait adds some accessor methods to VuFind params
 *
 * Trait ParamTrait
 *
 * @package VuFindCollapseExpand\Search\Solr
 *
 * @author Cornelius Amzar <cornelius.amzar@bsz-bw.de>
 * @author Robert Lange <lange@ub.uni-leipzig.de>
 *
 *
 * Controlling Result is changed from Result Grouping to Collapse and Expand
 * @author Steven Lolong <steven.lolong@uni-tuebingen.de>
 */
trait ParamsTrait
{
    // Construct collapse parameters
    public function constructingCollapseParams()
    {
        $collapseConfig = $this->collapseExpandConfig->getCollapseConfig();
        $currentSettings = $this->collapseExpandConfig->getCurrentSettings();

        $param = '{!collapse ';
        foreach (array_keys($collapseConfig) as $key) {
            if (isset($currentSettings[$key]) && $currentSettings[$key] != null) {
                $param .= explode('.', $key)[1] . '=' . $currentSettings[$key] . ' ';
            }
        }
        $param .= '}';
        return $param;
    }

    // Construct expand parameters
    public function constructingExpandParams()
    {
        $expandConfig = $this->collapseExpandConfig->getExpandConfig();
        $currentSettings = $this->collapseExpandConfig->getCurrentSettings();
        $params = [];

        foreach (array_keys($expandConfig) as $key) {
            if (isset($currentSettings[$key]) && $currentSettings[$key] != null) {
                $params[$key] = $currentSettings[$key];
            }
        }
        return $params;
    }

    /**
     * Create search backend parameters for advanced features.
     *
     * @return ParamBag
     */
    public function getBackendParameters()
    {
        $backendParams = parent::getBackendParameters();

        // restore grouping settings from cookie
        $this->collapseExpandConfig->restoreFromCookie();

        // check if grouping is enabled in the configuration && by the user in the session (frontend)
        if ($this->collapseExpandConfig->isEnabled() && $this->collapseExpandConfig->isActive()) {
            $backendParams->add('expand', 'true');

            // construct collapse parameters
            $backendParams->add('fq', $this->constructingCollapseParams());

            // enabling expand
            // construct expand parameters
            foreach ($this->constructingExpandParams() as $key => $value) {
                $backendParams->add($key, $value);
            }
        }

        return $backendParams;
    }

    public function isEnableCollapseExpand(): bool
    {
        return $this->collapseExpandConfig->isEnabled();
    }

    public function isActivatedCollapseExpand(): bool
    {
        return $this->collapseExpandConfig->isActive();
    }

    public function getExpandField(): string
    {
        return $this->collapseExpandConfig->getExpandField();
    }
}
