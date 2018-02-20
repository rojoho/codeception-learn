<?php declare(strict_types=1);


class InternalApiCest
{
    const authEmail = 'rob@hobro.ca';
    const authPassword = 's68oJArT2Xa9tKz';

    /**
     * The race (sub-event) id for the created race
     *
     * @var int|null
     */
    private $subEventId = null;

    public function tryToCreateRace(ApiTester $I)
    {
        $I->wantTo('create a results race');

        $I->login(self::authEmail, self::authPassword);

        $I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
        $I->sendPOST('/internal-api/sub-events', json_encode(
            ['data' => [
                'attributes'    => [
                    'name'                          => 'My new race',
                    'has-name'                      => false,
                    'has-team-name'                 => false,
                    'has-bib'                       => false,
                    'has-overall-place'             => false,
                    'has-division-place'            => false,
                    'has-gender-place'              => false,
                    'has-location'                  => false,
                    'has-division'                  => false,
                    'are-results-public'            => false,
                    'is-scoring-enabled'            => true,
                    'participant-count'             => null,
                    'participant-count-coed'        => null,
                    'participant-count-male'        => null,
                    'participant-count-female'      => null,
                    'has-gun-times'                 => false,
                    'has-chip-times'                => false,
                    'has-final-times'               => false,
                    'has-gender'                    => false,
                    'has-pace'                      => false,
                    'has-gender-in-division-labels' => false,
                    'has-boston-qualifier'          => false
                ],
                'relationships' => [
                    'event'          => ['data' => ['type' => 'events', 'id' => 'rr-583']],
                    'raw-result-set' => ['data' => null]
                ],
                'type'          => 'sub-events']]
        ));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $data = $I->grabDataFromResponseByJsonPath('$.data.id');
        $this->subEventId = (int) end($data);

        $I->expect(sprintf('a sub-event was created with id: [%d]', $this->subEventId));

        $I->sendGET(sprintf('/internal-api/sub-events/%d', $this->subEventId));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $data = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertEquals($this->subEventId, (int) end($data));
    }
}
