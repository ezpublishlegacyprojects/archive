<?php
// Modified on: <Tue Aug 22 21:21:57 CEST 2006 ls@ez.no>
//
// SOFTWARE NAME: eZ publish
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//  This program is free software; you can redistribute it and/or
//  modify it under the terms of version 2.0 of the GNU General
//  Public License as published by the Free Software Foundation.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//  GNU General Public License for more details.
//
//  You should have received a copy of version 2.0 of the GNU General
//  Public License along with this program; if not, write to the Free
//  Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//  MA 02110-1301, USA.
//
//

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentobjecttreenodeoperations.php' );
include_once( 'lib/ezutils/classes/ezini.php' );

$ini =& eZINI::instance( 'content.ini' );

$classIDList = $ini->variable( 'ArchiveSettings', 'ClassIDList' );
$subtreeNodeIDList = $ini->variable( 'ArchiveSettings', 'SubtreeNodeIDList' );
$archiveNodeIDList = $ini->variable( 'ArchiveSettings', 'ArchiveNodeIDList' );
$expireDateAttribute = $ini->variable( 'ArchiveSettings', 'ExpireDateAttribute' );

$currentUnixTimestamp = time();

foreach( $subtreeNodeIDList as $subtreeNodeID )
{
    $nodes =& eZContentObjectTreeNode::subTree( array( 'ClassFilterType' => 'include',
                                                       'ClassFilterArray' => $classIDList ), $subtreeNodeID );
    if ( !$nodes )
        continue;

    foreach ( $nodes as $node )
    {
        $object =& $node->object();

        if ( !$object )
            continue;

        $archiveNodeID = $archiveNodeIDList[$subtreeNodeID];

        $dataMap =& $object->attribute( 'data_map' );

        if ( !isset( $dataMap[$expireDateAttribute] ) )
            continue;

        $dataTypeString = $dataMap[$expireDateAttribute]->attribute( 'data_type_string' );

        if ( ( $dataTypeString = 'ezdatetime' ) || ( $dataTypeString = 'ezdate' ) )
        {
            $expireDateAttributeContent =& $dataMap[$expireDateAttribute]->content();
            $objectExpireTimestamp = $expireDateAttributeContent->attribute( 'timestamp' );

            if ( ( $objectExpireTimestamp > 0 ) && ( $objectExpireTimestamp < $currentUnixTimestamp ) )
            {
                eZContentObjectTreeNodeOperations::move( $node->attribute('node_id'), $archiveNodeID );
            }
        }
    }
}

?>