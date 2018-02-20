<?php declare(strict_types=1);


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Performs login action on signin page
     *
     * @param string $email
     * @param string $password
     */
    public function login(string $email, string $password): void
    {
        $I = $this;

        $I->amOnPage('/signin');
        $I->canSeeElement('#sign-in-button');

        // fetch token value from form
        $token = $I->grabValueFrom(['css' => 'input[name="_token"]']);

        // submit signin form
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->submitForm('#authentication-form', array(
            'email'    => $email,
            'password' => $password,
            '_token'   => $token
        ), 'submitButton');

        $I->canSeeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->canSeeCookie('RRSS_DEV');
    }
}
