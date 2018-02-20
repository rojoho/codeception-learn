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
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Performs login action on signin page
     *
     * @param string $email
     * @param string $password
     */
    public function login(string $email, string $password): void
    {
        $I = $this;

        $I->sendGET('/signin');
        $I->seeResponseIsHtml();
        $I->seeResponseContains('id="sign-in-button"');

        // submit signin form
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendPOST('/signin', [
            'email'    => $email,
            'password' => $password,
            '_token'   => $I->grabValueFromInputWithNameInHtml('_token', $I->grabResponse())
        ]);

        $I->canSeeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    }
}
