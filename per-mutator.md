# Effects per Mutator

| Mutator                         | Mutations | Killed | Escaped | Errors | Syntax Errors | Timed Out | Skipped | Ignored | MSI (%s) | Covered MSI (%s) |
| ------------------------------- | --------- | ------ | ------- | ------ | ------------- | --------- | ------- | ------- | -------- | ---------------- |
| ArrayItem                       |         4 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    25.00 |            50.00 |
| ArrayItemRemoval                |        18 |      8 |       3 |      0 |             0 |         0 |       0 |       0 |    44.44 |            72.73 |
| Break_                          |         1 |      0 |       1 |      0 |             0 |         0 |       0 |       0 |     0.00 |             0.00 |
| CastInt                         |         3 |      0 |       2 |      0 |             0 |         0 |       0 |       0 |     0.00 |             0.00 |
| CastString                      |         1 |      1 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| Coalesce                        |         2 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    50.00 |            50.00 |
| Concat                          |         3 |      3 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| ConcatOperandRemoval            |         5 |      5 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| DecrementInteger                |        28 |      5 |       6 |      0 |             0 |         0 |       0 |       0 |    17.86 |            45.45 |
| Division                        |         2 |      2 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| FalseValue                      |         4 |      2 |       1 |      0 |             0 |         0 |       0 |       0 |    50.00 |            66.67 |
| Foreach_                        |         5 |      3 |       0 |      0 |             0 |         0 |       0 |       0 |    60.00 |           100.00 |
| GreaterThan                     |         2 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    50.00 |            50.00 |
| GreaterThanNegotiation          |         2 |      2 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| GreaterThanOrEqualTo            |         1 |      0 |       1 |      0 |             0 |         0 |       0 |       0 |     0.00 |             0.00 |
| GreaterThanOrEqualToNegotiation |         1 |      0 |       1 |      0 |             0 |         0 |       0 |       0 |     0.00 |             0.00 |
| Identical                       |         4 |      3 |       0 |      0 |             0 |         0 |       0 |       0 |    75.00 |           100.00 |
| IfNegation                      |         3 |      3 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| IncrementInteger                |        28 |      4 |       7 |      0 |             0 |         0 |       0 |       0 |    14.29 |            36.36 |
| InstanceOf_                     |         4 |      2 |       2 |      0 |             0 |         0 |       0 |       0 |    50.00 |            50.00 |
| LogicalAnd                      |         2 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    50.00 |            50.00 |
| LogicalAndAllSubExprNegation    |         2 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    50.00 |            50.00 |
| LogicalAndNegation              |         2 |      2 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| LogicalNot                      |         6 |      6 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| LogicalOr                       |         3 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    33.33 |            50.00 |
| LogicalOrAllSubExprNegation     |         3 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    33.33 |            50.00 |
| LogicalOrNegation               |         3 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    33.33 |            50.00 |
| LogicalOrSingleSubExprNegation  |         3 |      1 |       1 |      0 |             0 |         0 |       0 |       0 |    33.33 |            50.00 |
| MBString                        |         2 |      0 |       2 |      0 |             0 |         0 |       0 |       0 |     0.00 |             0.00 |
| MethodCallRemoval               |        45 |     19 |      10 |      0 |             0 |         0 |       0 |       0 |    42.22 |            65.52 |
| Minus                           |         1 |      1 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| Multiplication                  |         1 |      0 |       1 |      0 |             0 |         0 |       0 |       0 |     0.00 |             0.00 |
| NewObject                       |         3 |      2 |       0 |      0 |             0 |         0 |       0 |       0 |    66.67 |           100.00 |
| NotIdentical                    |         1 |      0 |       1 |      0 |             0 |         0 |       0 |       0 |     0.00 |             0.00 |
| OneZeroFloat                    |         1 |      0 |       1 |      0 |             0 |         0 |       0 |       0 |     0.00 |             0.00 |
| PublicVisibility                |       210 |    157 |      12 |      0 |             0 |         0 |       0 |       0 |    74.76 |            92.90 |
| RoundingFamily                  |         2 |      2 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
| Ternary                         |         5 |      4 |       1 |      0 |             0 |         0 |       0 |       0 |    80.00 |            80.00 |
| Throw_                          |         9 |      6 |       0 |      0 |             0 |         0 |       0 |       0 |    66.67 |           100.00 |
| TrueValue                       |         2 |      1 |       0 |      0 |             0 |         0 |       0 |       0 |    50.00 |           100.00 |
| UnwrapArrayMap                  |         4 |      4 |       0 |      0 |             0 |         0 |       0 |       0 |   100.00 |           100.00 |
