<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTest extends WebTestCase
{
    public function testDoctorIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/doctor/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table.table');
        $this->assertGreaterThan(0, $crawler->filter('table.table tbody tr')->count(), 'The table must contain at least one row for a Doctor.');

        $this->assertSelectorTextContains('table.table thead tr th:first-child', 'Id');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(2)', 'Nom');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(3)', 'Prenom');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(4)', 'actions');
    }

    public function testDateSearchIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/datesearch/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table.table');
        $this->assertGreaterThan(0, $crawler->filter('table.table tbody tr')->count(), 'The table must contain at least one row for a DateSearch.');

        $this->assertSelectorTextContains('table.table thead tr th:first-child', 'Id');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(2)', 'DateSearch1');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(3)', 'actions');
    }

    public function testScheduleIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/schedule/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table.table');
        $this->assertGreaterThan(0, $crawler->filter('table.table tbody tr')->count(), 'The table must contain at least one row for a Schedule.');

        $this->assertSelectorTextContains('table.table thead tr th:first-child', 'Id');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(2)', 'ScheduleDate');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(3)', 'ScheduleDay');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(4)', 'actions');
    }

    public function testUsersIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table.table');
        $this->assertGreaterThan(0, $crawler->filter('table.table tbody tr')->count(), 'The table must contain at least one row for a User.');

        $this->assertSelectorTextContains('table.table thead tr th:first-child', 'Id');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(2)', 'Username');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(3)', 'Email');
        $this->assertSelectorTextContains('table.table thead tr th:nth-child(4)', 'actions');
    }
}
