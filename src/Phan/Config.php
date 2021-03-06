<?php declare(strict_types=1);
namespace Phan;

/**
 * Program configuration
 */
class Config {

    /**
     * @var string
     * The root directory of the project. This is used to
     * store canonical path names and find project resources
     */
    private $project_root_directory = null;

    /**
     * Configuration options
     */
    private $configuration = [

        // A list of directories holding code that we want
        // to parse, but not analyze
        'exclude_analysis_directory_list' => [],

        // Backwards Compatibility Checking
        'backward_compatibility_checks' => true,

        // A set of fully qualified class-names for which
        // a call to parent::__construct() is required
        'parent_constructor_required' => [],

        // Run a quick version of checks that takes less
        // time
        'quick_mode' => false,

        // If true, missing properties will be created when
        // they are first seen. If false, we'll report an
        // error message.
        'allow_missing_properties' => true,

        // Allow null to be cast as any type and for any
        // type to be cast to null.
        'null_casts_as_any_type' => true,

        // If a file path is given, the code base will be
        // read from and written to the given location in
        // order to attempt to save some work from being
        // done. Only changed files will get analyzed if
        // the file is read
        'stored_state_file_path' => null,

        // Set to true in order to force a re-analysis of
        // any file passed in via the CLI even if our
        // internal state is up-to-date
        'reanalyze_file_list' => true,

        // If set to true, we'll dump the AST instead of
        // analyzing files
        'dump_ast' => false,

        // Include a progress bar in the output
        'progress_bar' => false,

        // The probability of actually emitting any
        // progress bar update
        'progress_bar_sample_rate' => 0.1,

        // Set to true in order to prepend all emitted error
        // messages with an ID indicating the distinct class
        // of issue seen. This allows us to get counts of
        // distinct error types.
        'emit_trace_id' => false,

        // The vesion of the AST (defined in php-ast)
        // we're using
        'ast_version' => 30,
    ];

    /**
     * Disallow the constructor to force a singleton
     */
    private function __construct() {}

    /**
     * @return string
     * Get the root directory of the project that we're
     * scanning
     */
    public function getProjectRootDirectory() : string {
        return $this->project_root_directory ?? getcwd();
    }

    /**
     * @param string $project_root_directory
     * Set the root directory of the project that we're
     * scanning
     *
     * @return void
     */
    public function setProjectRootDirectory(
        string $project_root_directory
    ) {
        $this->project_root_directory = $project_root_directory;
    }

    /**
     * @return Configuration
     * Get a Configuration singleton
     */
    public static function get() : Config {
        static $instance;

        if ($instance) {
            return $instance;
        }

        $instance = new Config();
        return $instance;
    }

    public function __get(string $name) {
        return $this->configuration[$name];
    }

    public function __set(string $name, $value) {
        $this->configuration[$name] = $value;
    }

    /**
     * @return string
     * The relative path appended to the project root directory.
     */
    public static function projectPath(string $relative_path) {
        return implode(DIRECTORY_SEPARATOR, [
            Config::get()->getProjectRootDirectory(),
            $relative_path
        ]);
    }
}
