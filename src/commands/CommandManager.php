<?php namespace Rolice\LangSync;

use Illuminate\Console\Command;

class CommandManager extends Command
{

    protected $parser;
    protected $sync;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'locale:extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract and sync locale labels';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->parser = new Parser(new LabelsCollection());
        $this->sync = new Sync();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->parser->extract();
        $data = $this->parser->get();

        $this->sync->execute($data);
    }

}