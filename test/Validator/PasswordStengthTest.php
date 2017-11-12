<?php
/**
 *  kanellov/password-strength-validator.
 *
 * @link https://github.com/kanellov/password-strength-validator for the canonical source repository
 *
 * @copyright Copyright (c) 2017 International Labour Organization
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace KnlvTest\Validator;

use Knlv\Validator\PasswordStrength;

class PasswordStengthTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return array(
            array('password', PWD_CONTAIN_UC, null, false, PasswordStrength::NO_CONTAIN_UC),
            array('password', PWD_CONTAIN_LC, null, true, null),
            array('Password', PWD_CONTAIN_UC, null, true, null),
            array('PASSWORD', PWD_CONTAIN_LC, null, false, PasswordStrength::NO_CONTAIN_LC),
            array('password', PWD_CONTAIN_DGT, null, false, PasswordStrength::NO_CONTAIN_DGT),
            array('p4ssword', PWD_CONTAIN_DGT, null, true, null),
            array('password', PWD_CONTAIN_SYM, null, false, PasswordStrength::NO_CONTAIN_SYM),
            array('p@ssword', PWD_CONTAIN_SYM, null, true, null),
            array('password', PWD_CONTAIN_DGT_OR_SYM, null, false, PasswordStrength::NO_CONTAIN_DGT_OR_SYM),
            array('p4ssword', PWD_CONTAIN_DGT_OR_SYM, null, true, null),
            array('p@ssowrd', PWD_CONTAIN_DGT_OR_SYM, null, true, null),
            array('password', PWD_CONTAIN_DGT | PWD_CONTAIN_SYM, null, false, PasswordStrength::NO_CONTAIN_DGT),
            array('p@ssw0rd', PWD_CONTAIN_DGT | PWD_CONTAIN_SYM, null, true, null),
            array('p@ssw0rd', PWD_CONTAIN_DGT | PWD_CONTAIN_SYM | PWD_CONTAIN_UC, null, false, PasswordStrength::NO_CONTAIN_UC),
            array('P@ssw0rd', PWD_CONTAIN_DGT | PWD_CONTAIN_SYM | PWD_CONTAIN_UC, null, true, null),
            array('password!', PWD_CONTAIN_SYM, null, true, null),
            array('password!', PWD_CONTAIN_SYM, '!', false, PasswordStrength::NO_CONTAIN_SYM),
        );
    }
    public function testSetGetExcludedSymbols()
    {
        $validator = new PasswordStrength();
        $expected = '?%';
        $validator->setExcludedSymbols($expected);
        $this->assertEquals($expected, $validator->getExcludedSymbols());
    }

    public function testSetGetFlags()
    {
        $validator = new PasswordStrength();
        $expected = PWD_CONTAIN_SYM | PWD_CONTAIN_UC;
        $validator->setFlags($expected);
        $this->assertEquals($expected, $validator->getFlags());
    }

    /**
     * @dataProvider provider
     *
     * @param $password
     */
    public function testIsValid($password, $flags, $exclude_symbols, $expected_is_valid, $expected_error_message)
    {
        $validator = new PasswordStrength(array('flags' => $flags, 'excludedSymbols' => $exclude_symbols));
        $message_templages = $validator->getMessageTemplates();
        $is_valid = $validator->isValid($password);
        $this->assertEquals($expected_is_valid, $is_valid);
        if (!$is_valid) {
            $this->assertContains($message_templages[$expected_error_message], $validator->getMessages());
        }
    }
}
