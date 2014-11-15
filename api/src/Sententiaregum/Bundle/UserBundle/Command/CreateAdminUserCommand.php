<?php

namespace Sententiaregum\Bundle\UserBundle\Command;

use Sententiaregum\Bundle\UserBundle\Exception\ExistingUserException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sententiaregum:user:create-admin')
            ->setDescription('Creates a new administrator user')
            ->addOption('name', 'u', InputOption::VALUE_REQUIRED, 'Name of the administrator')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Password of the administrator')
            ->addOption('email', 'm', InputOption::VALUE_REQUIRED, 'Email of the administrator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name     = $input->getOption('name');
        $password = $input->getOption('password');
        $email    = $input->getOption('email');
        $roles = array_unique(array_merge(['ROLE_ADMIN'], $this->getContainer()->getParameter('registration.defaultRoles')));

        /** @var \Sententiaregum\Bundle\UserBundle\Entity\UserRepository $userRepository */
        $userRepository = $this->getContainer()->get('sen.user.repository');
        /** @var \Sententiaregum\Bundle\UserBundle\Service\CreateAccountInterface $createAccountService */
        $createAccountService = $this->getContainer()->get('sen.service.create_account');

        $entity = $userRepository->create($name, $password, $email, new \DateTime());
        $errors = $createAccountService->validateInput($entity);
        if (count($errors) > 0) {
            throw new \RuntimeException('Invalid data entered: ' . implode(', ', $errors));
        }

        $entity->setRoles($roles);
        $createAccountService->persist($entity);

        $output->writeln('<fg=green>New admin user with name "<fg=cyan>' . $name . '</fg=cyan>" added</fg=green>');
    }
}
