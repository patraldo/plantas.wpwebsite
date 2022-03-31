<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk;

use Inpsyde\Zettle\PhpSdk\DB\Table;
use wpdb;

class Bootstrap
{
    /** @var $tables */
    private $tables;

    /**
     * Bootstrap constructor.
     *
     * @param Table ...$tables
     */
    public function __construct(Table ...$tables)
    {
        $this->tables = $tables;
    }

    public function activate()
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $this->createTables($wpdb);
    }

    /**
     * @param wpdb $wpdb
     */
    private function createTables(wpdb $wpdb)
    {
        $charsetCollate = $wpdb->get_charset_collate();
        $prefix = $wpdb->get_blog_prefix();

        foreach ($this->tables as $table) {
            //phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
            $sql = "CREATE TABLE IF NOT EXISTS {$prefix}{$table->name()} ({$table->schema()}) $charsetCollate;";
            dbDelta($sql);
        }
    }

    /**
     * phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
     */
    public function deactivate()
    {
        global $wpdb;

        $prefix = $wpdb->get_blog_prefix();

        foreach ($this->tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS {$prefix}{$table->name()}");
        }
    }
}
