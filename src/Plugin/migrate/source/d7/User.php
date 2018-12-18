<?php

namespace Drupal\dmeblog_migrate\Plugin\migrate\source\d7;

use Drupal\migrate\Row;
use Drupal\user\Plugin\migrate\source\d7\User as D7User;

/**
 * Fetch only users that have authored a blog post.
 *
 * Rather than start from scratch we extend the Drupal\user\Plugin\migrate\source\d7\User
 * class and override the query method. Then change the query that gets used to
 * select only users who have authored one or more blog posts.
 *
 * @MigrateSource(
 *   id = "custom_d7_user"
 * )
 */
class User extends D7User {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Our custom query returns the set of fields as
    // Drupal\user\Plugin\migrate\source\d7\User::query(), but contains some
    // extra logic. Including a join to the node table, and a new condition that
    // effectively limits the rows returned to only those users who have
    // authored one-or-more blog posts.
    $query = $this->select('users', 'u');
    $query->join('node', 'n', 'n.uid = u.uid');
    $query->fields('u');
    $query->distinct()
      ->condition('n.type', 'blog_post', '=');
    return $query;
  }

  public function prepareRow(Row $row) {
    parent::prepareRow($row);
    return FALSE;
  }


}
