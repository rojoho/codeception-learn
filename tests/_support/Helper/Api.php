<?php declare(strict_types=1);

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module
{
    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function seeResponseIsHtml()
    {
        $response = $this->getModule('REST')->response;
        $this->assertRegExp('#(<!DOCTYPE html.*?>)?\s*<html(.*)<\/html>#is', $response);
    }

    /**
     * @param $id
     * @param $html
     * @return string|null
     */
    public function grabValueFromInputWithNameInHtml($name, $html): ?string
    {
        preg_match(sprintf('/<input(.*?)name=\"%s\"(.*)value=\"(.*?)\"/i', $name), $html, $matches);

        return isset($matches[3]) ? $matches[3] : null;
    }

    public function seeCookie($cookie)
    {
        $this->assertNotEmpty($this->getClient()->getCookieJar()->get($cookie), sprintf('Cookie [%s] not found', $cookie));
    }

    /**
     * @return \Symfony\Component\HttpKernel\Client|\Symfony\Component\BrowserKit\Client $client
     */
    protected function getClient()
    {
        return $this->getModule('REST')->client;
    }
}
