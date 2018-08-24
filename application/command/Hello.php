<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-24
 * Time: 下午2:35
 */
namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;


class Hello extends Command{
    /**
     * 重写configure
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('hello')
            ->setDescription('hello word !');
    }


    /**
     * 重写execute
     * {@inheritdoc}
     */
    protected function execute(Input $input, Output $output)
    {
        $output->writeln('hello word !');
    }
}
