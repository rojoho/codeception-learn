<?php declare(strict_types=1);


class ResultsAdminCest
{
    const authEmail = 'rob@hobro.ca';
    const authPassword = 's68oJArT2Xa9tKz';

    /**
     * The generated race roster event id
     *
     * @var int|null
     */
    private $rrEventId = null;

    /**
     * The generated result sub-event
     *
     * @var int|null
     */
    private $subEventId = null;

    /**
     * Tries to signin to the race roster core application
     *
     * @param AcceptanceTester $I
     */
    public function tryToSignin(AcceptanceTester $I)
    {
        $I->wantTo('sign in');

        $I->login(self::authEmail, self::authPassword);

        $I->amOnPage('/dashboard/RaceDirector/Home.php');
        $I->cantSeeElement('#sign-in-button');
        $I->canSee('Sign Out');
    }

    /**
     * Tries to create a race roster event
     *
     * @param AcceptanceTester $I
     */
    public function tryToCreateRaceRosterEvent(AcceptanceTester $I)
    {
        $I->wantTo('create a new race roster event');

        $I->login(self::authEmail, self::authPassword);

        $I->amOnPage('/dashboard/event-organizers/event/new');
        $I->canSeeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->canSee('Event name');

        $I->submitForm('form[name=create_event]', ['create_event' => ['name' => 'My New Event']], 'create_event[_s]');
        $I->canSeeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $this->rrEventId = $I->grabFromCurrentUrl('~\/dashboard\/event-organizers\/event\/(\d+)\/features~');
    }

    /**
     * Tries to view the results admin dashboard for the created race roster event
     *
     * @param AcceptanceTester $I
     */
    public function tryToViewResultsAdminDashboard(AcceptanceTester $I)
    {
        $I->wantTo(sprintf('view the results admin dashboard for race roster event [%d]', $this->rrEventId));

        $I->login(self::authEmail, self::authPassword);

        $I->amOnPage(sprintf('/dashboard/RaceDirector/rr-%d/results', $this->rrEventId));
        $I->canSeeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    }

    /**
     * Tries to delete the race roster event that was created
     *
     * @param AcceptanceTester $I
     */
    public function tryToDeleteRaceRosterEvent(AcceptanceTester $I)
    {
        $I->wantTo(sprintf('mark race roster event [%d] as deleted', $this->rrEventId));

        $I->login(self::authEmail, self::authPassword);

        $I->sendAjaxPostRequest(sprintf('/dashboard/event-organizers/event/%d/delete', $this->rrEventId));

        $I->amOnPage(sprintf('/%d', $this->rrEventId));
        $I->see('The event you were looking for does not exist.');
    }
}
