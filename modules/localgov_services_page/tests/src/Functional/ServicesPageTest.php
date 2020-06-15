<?php

namespace Drupal\Tests\localgov_services_page\Functional;

use Drupal\node\NodeInterface;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\node\Traits\NodeCreationTrait;

/**
 * Tests localgov services pages.
 *
 * @group localgov_services
 */
class ServicesPageTest extends BrowserTestBase {

  use NodeCreationTrait;


  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'field_ui',
    'localgov_services_page',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser([
      'access administration pages',
      'administer node fields',
    ]);
  }

  /**
   * Test necessary fields exist and display correctly.
   */
  public function testServicesPage() {
    $this->drupalLogin($this->adminUser);

    // Check all fields exist.
    $this->drupalGet('/admin/structure/types/manage/localgov_services_page/fields');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('body');
    $this->assertSession()->pageTextContains('field_common_tasks');
    $this->assertSession()->pageTextContains('field_hide_related_topics');
    $this->assertSession()->pageTextContains('field_page_components');
    $this->assertSession()->pageTextContains('field_related_links');
    $this->assertSession()->pageTextContains('field_override_related_links');
    $this->assertSession()->pageTextContains('field_topic_term');
    $this->assertSession()->pageTextContains('localgov_services_parent');

    // Check status page.
    $title = $this->randomMachineName(8);
    $summary = $this->randomMachineName(16);
    $body = $this->randomMachineName(32);
    $page = $this->createNode([
      'type' => 'localgov_services_page',
      'title' => $title,
      'body' => [
        'summary' => $summary,
        'value' => $body,
      ],
      'status' => NodeInterface::PUBLISHED,
    ]);
    $this->drupalGet('/node/' . $page->id());
    $this->assertSession()->pageTextContains($title);
    $this->assertSession()->pageTextContains($body);
  }

}
