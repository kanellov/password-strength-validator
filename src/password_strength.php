<?php
/**
 *  kanellov/password-strength-validator.
 *
 * @link https://github.com/kanellov/password-strength-validator for the canonical source repository
 *
 * @copyright Copyright (c) 2017 International Labour Organization
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace Knlv;

/*
 * Password should contain at least one digit
 */
define('PWD_CONTAIN_DGT', 0x01);
/*
 * Password should contain at least one lowercase character
 */
define('PWD_CONTAIN_LC', 0x02);
/*
 * Password should contain at least one uppercase character
 */
define('PWD_CONTAIN_UC', 0x04);
/*
 * Password should contain at least one symbol character
 */
define('PWD_CONTAIN_SYM', 0x08);
/*
 * Password should container either one digit or one symbol character
 */
define('PWD_CONTAIN_DGT_OR_SYM', 0x10);

/**
 * Checks if password is strong enough according the given flags.
 *
 * @param string      $password        the password to check
 * @param int         $flags           [optional] - Default PWD_CONTAIN_DGT <p>
 *                                     Specify either a bitmask, or of the following named constants for what should the password contain
 *                                     PWD_CONTAIN_DGT,
 *                                     PWD_CONTAIN_LC,
 *                                     PWD_CONTAIN_UC,
 *                                     PWD_CONTAIN_SYM,
 *                                     PWD_CONTAIN_NUM_OR_SYM
 *                                     </p>
 * @param string|null $exclude_symbols [optional] <p>
 *                                     Specify which symbols should be excluded
 *                                     </p>
 *
 * @throws \ErrorException if password is not strong enough
 */
function password_strength($password, $flags = 0x01, $exclude_symbols = null)
{
    $symbols = str_split('!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~');

    if (is_string($exclude_symbols) && !empty($exclude_symbols)) {
        $symbols = array_diff($symbols, str_split($exclude_symbols));
    }

    $has_digits = 1 === preg_match('/[0-9]/', $password);
    if ($flags & PWD_CONTAIN_DGT && !$has_digits) {
        throw new \ErrorException('Password must contain at least one digit character', PWD_CONTAIN_DGT);
    }

    $has_lc = 1 === preg_match('/[a-z]/', $password);
    if ($flags & PWD_CONTAIN_LC && !$has_lc) {
        throw new \ErrorException('Password must contain at least one lowercase character', PWD_CONTAIN_LC);
    }

    $has_uc = 1 === preg_match('/[A-Z]/', $password);
    if ($flags & PWD_CONTAIN_UC && !$has_uc) {
        throw new \ErrorException('Password must contain at least one uppercase character', PWD_CONTAIN_UC);
    }

    $symbols = array_intersect(str_split($password), $symbols);
    $has_symbols = !empty($symbols);
    if ($flags & PWD_CONTAIN_SYM && !$has_symbols) {
        throw new \ErrorException('Password must contain at least one symbol character', PWD_CONTAIN_SYM);
    }

    if ($flags & PWD_CONTAIN_DGT_OR_SYM && !$has_symbols && !$has_digits) {
        throw new \ErrorException('Password must contain at least one digit or symbol character', PWD_CONTAIN_DGT_OR_SYM);
    }
}
