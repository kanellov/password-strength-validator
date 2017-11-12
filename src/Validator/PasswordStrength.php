<?php
/**
 *  kanellov/password-strength-validator.
 *
 * @link https://github.com/kanellov/password-strength-validator for the canonical source repository
 *
 * @copyright Copyright (c) 2017 International Labour Organization
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace Knlv\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Class PasswordStrength
 * Implements a \Zend\Validator\ValidatorInterface by wrapping the \Knlv\password_strength function
 * @package Knlv\Validator
 */
class PasswordStrength extends AbstractValidator
{
    const NO_CONTAIN_DGT = 0x01;
    const NO_CONTAIN_LC = 0x02;
    const NO_CONTAIN_UC = 0x04;
    const NO_CONTAIN_SYM = 0x08;
    const NO_CONTAIN_DGT_OR_SYM = 0x10;

    /**
     * The message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_CONTAIN_DGT => 'Password must contain at least one digit character',
        self::NO_CONTAIN_LC => 'Password must contain at least one lowercase character',
        self::NO_CONTAIN_UC => 'Password must contain at least one uppsercase character',
        self::NO_CONTAIN_SYM => 'Password must contain at least one symbol character',
        self::NO_CONTAIN_DGT_OR_SYM => 'Password must contain at least either one digit or symbol character',
    );

    /**
     * The excluded symbols
     * @var string
     */
    protected $excludedSymbols;

    /**
     * The flags to pass to \Knlv\password_strength
     * @var int
     */
    protected $flags;

    /**
     * Set the list symbols to exclude.
     *
     * @param string $excludedSymbols
     *
     * @return self
     */
    public function setExcludedSymbols($excludedSymbols)
    {
        $this->excludedSymbols = (string) $excludedSymbols;

        return $this;
    }

    /**
     * Returns the list of excluded symbols.
     *
     * @return string
     */
    public function getExcludedSymbols()
    {
        return $this->excludedSymbols;
    }

    /**
     * Sets the flags bitmask.
     *
     * @param int $flags <p>
     *                   Specify either a bitmask, or of the following named constants for what should the password contain
     *                   PWD_CONTAIN_DGT,
     *                   PWD_CONTAIN_LC,
     *                   PWD_CONTAIN_UC,
     *                   PWD_CONTAIN_SYM,
     *                   PWD_CONTAIN_NUM_OR_SYM
     *                   </p>
     *
     * @return self
     */
    public function setFlags($flags)
    {
        $this->flags = (int) $flags;

        return $this;
    }

    /**
     * Return the flags bitmask.
     *
     * @return int
     */
    public function getFlags()
    {
        return $this->flags;
    }

    public function isValid($value)
    {
        $flags = $this->getFlags();
        $excluded_symbols = $this->getExcludedSymbols();
        $this->value = $value;
        try {
            \Knlv\password_strength($value, $flags, $excluded_symbols);
        } catch (\ErrorException $e) {
            $this->error($e->getCode());

            return false;
        }

        return true;
    }
}
