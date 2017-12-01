<?php

namespace AppBundle\Command;

use AppBundle\Entity\Dictionary;
use AppBundle\Entity\Word;
use AppBundle\Event\DictionaryLoaded;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadFileCommand
 * @package AppBundle\Command
 *
 * Load words into dictionary
 */
class LoadFileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import-dictionary')
            ->setDescription('Load dictionary.')
            ->addArgument('file', InputArgument::REQUIRED, 'File to load.')
            ->addArgument('code', InputArgument::REQUIRED, 'Dictionary identifier.')
        ;
    }

    private function readFile($handler)
    {
        while ($line = fgets($handler)) {
            yield substr($line, 0, strpos($line, '/') ?: strpos($line, '\t'));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cnt = 0;
        $time = time();
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $file = $input->getArgument('file');
        $code = $input->getArgument('code');

        $fd = fopen($file, 'r');

        if ($fd === false) {
            return;
        }

        $dictionary = new Dictionary();
        $dictionary->setCode($code);
        $em->persist($dictionary);
        $em->flush();

        $generator = $this->readFile($fd);
        foreach ($generator as $value)
        {
            if ($value === '') {
                continue;
            }

            $tmp = new Word();
            $tmp->setWord($value);
            $tmp->setDictionary($dictionary);
            $dictionary->getWords()->add($tmp);

            $em->persist($tmp);

            if ($cnt === 1000) {
                $em->flush();
                $em->clear();
                $dictionary = $em->getRepository('AppBundle:Dictionary')
                    ->findOneByCode($code);
                $cnt = 0;
                $output->writeln(memory_get_peak_usage().' '.(time()-$time));
                $time = time();
            }
            $cnt++;
        }

        $em->flush();
        $em->clear();

        $output->writeln($dictionary->getId());

        $event = new DictionaryLoaded($dictionary);
        $this->getContainer()->get('event_dispatcher')->dispatch(DictionaryLoaded::NAME, $event);

        $output->writeln(memory_get_peak_usage());
        $output->writeln('end');
    }
}
