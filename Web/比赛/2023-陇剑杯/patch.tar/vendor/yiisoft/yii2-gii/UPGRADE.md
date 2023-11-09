Upgrading Instructions
======================

This file contains the upgrade notes. These notes highlight changes that could break your
application when you upgrade the package from one version to another.

Upgrade to 2.2.0
----------------

* The return value of `generators/model/Generator::checkJunctionTable()` has changed.
  This will only have an impact if you have extended the `generators/model/Generator` class
  and use the return value of `checkJunctionTable()`.
  
  Before version 2.2.0 the return value had the following structure (sample data, content may vary)

  ```php
  [ //list of junctions
      0 => [ // first junction
          0 => [ // "left" side of junction
              0              => 'schema1.multi_pk', //table name
              'multi_pk_id1' => 'id1', //foreign key
              'multi_pk_id2' => 'id2', //foreign key
          ],
          1 => [ // "right" side of junction
              0           => 'schema1.table1', //table name
              'table1_id' => 'id', //foreign key
          ],
      ],
  ];
  ```
  
  Since version 2.2.0 the "left" and "right" side junctions no longer contain the 'foreign key mapping' directly
  but contains an array of witch index 0 is the 'foreign key mapping' and index 1 the name of the foreign key.
  (sample data, content may vary)
  ```php
  [ //list of junctions
      0 => [ // first junction
          0 => [ // "left" side of junction
              0 => [ // foreign key mapping
                  0              => 'schema1.multi_pk', //table name
                  'multi_pk_id1' => 'id1', //foreign key
                  'multi_pk_id2' => 'id2', //foreign key
              ],
              1 => 'schema1.junction1.j1_multi_pk_fkey' //New: Name of the foreign key
          ],
          1 => [ // "right" side of junction
              0 => [ // foreign key mapping
                  0           => 'schema1.table1', //table name
                  'table1_id' => 'id', //foreign key
              ],
              1 => 'schema1.junction1.j1_table1_fkey' //New: Name of the foreign key
          ],
      ],
  ];
  ```
  
