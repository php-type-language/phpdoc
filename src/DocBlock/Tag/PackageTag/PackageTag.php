<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\PackageTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to categorize _Element(s)_ into logical subdivisions.
 *
 * The "`@package`" tag can be used as a counterpart or supplement to
 * Namespaces. Namespaces provide a functional subdivision of _Element(s)_
 * where the "`@package`" tag can provide a logical subdivision in which
 * way the elements can be grouped with a different hierarchy.
 *
 * If, across the board, both logical and functional subdivisions
 * are equal is it NOT RECOMMENDED to use the "`@package`"
 * tag to prevent maintenance overhead.
 *
 * Each level in the logical hierarchy MUST be separated with a backslash
 * (`\`) to be familiar to Namespaces. A hierarchy MAY be of endless depth
 * but it is RECOMMENDED to keep the depth at less or equal than six levels.
 *
 * Please note that the "`@package`" tag applies to different _Element(s)_
 * depending where it is defined.
 *
 * - If the package is defined in the file-level DocBlock then it only applies
 *   to the following elements in the applicable file:
 *    - global functions
 *    - global constants
 *    - global variables
 *    - requires and includes
 *
 * - If the package is defined in a namespace-level or class-level DocBlock
 *   then the package applies to that namespace, class, trait or interface
 *   and their contained elements. This means that a function which is
 *   contained in a namespace with the "`@package`" tag assumes that package.
 *
 * The "`@package`" tag MUST NOT occur more than once in a PHPDoc.
 *
 * ```
 * "@package" <namespace> [<description>]
 * ```
 */
final class PackageTag extends SubPackageTag {}
