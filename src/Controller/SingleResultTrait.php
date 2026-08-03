<?php

/*
 * Copyright 2026 (C) Leipzig University Library
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

namespace VuFindCollapseExpand\Controller;

/**
 * Trait SingleResultTrait
 *
 * @package VuFindCollapseExpand\Controller
 * @author  Alexander Purr <purr@ub.uni-leipzig.de>
 */
trait SingleResultTrait
{
    /**
     * prevent forwarding if subrecords exists:
     * use parent processJumpToOnlyResult if the first result has no subrecord(s)
     * to figure out if forwarding is configured in
     * config.ini [Record] processJumpToOnlyResult
     *
     * @param \VuFind\Search\Base\Results $results Search results object.
     *
     * @return bool|\Laminas\Http\Response
     */
    protected function processJumpToOnlyResult($results)
    {
        if (
            $results->getResultTotal() == 1
            && $results->getResults()[0]->hasSubRecords()
        ) {
            return false;
        }

        return parent::processJumpToOnlyResult($results);
    }
}
