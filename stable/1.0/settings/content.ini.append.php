<?php /*

# Cronjob script archive.php moves content objects of specified class
# from specified root node e.g "News" folder to specified container
# e.g "Archive" folder base on expire_date (ezdatetime or ezdate) attribute
#
# TopNode
#   | - News [Folder, NodeID:22]
#   |     | - Article 1 [Article]
#   |     | - Article 2 [Article]
#   |
#   | - Archive [Folder, NodeID:99]
#

[ArchiveSettings]
# Content objects of those classes will be moved to archive
ClassIDList[]
ClassIDList[]=article

# Subtree node IDs where are content objects stored, e.g "News" folder
SubtreeNodeIDList[]
SubtreeNodeIDList[]=22

# NodeIDs of archive containers, array key refers to SubtreeNodeIDList
# Archive container can be setup per subtree
ArchiveNodeIDList[22]=99

# Content class attribute identifier of ezdatetime or ezdate datatype
# Add ezdatetime or ezdate attribute into your content class
# Base on that date script will move content object to archive container
ExpireDateAttribute=expire_date

*/ ?>