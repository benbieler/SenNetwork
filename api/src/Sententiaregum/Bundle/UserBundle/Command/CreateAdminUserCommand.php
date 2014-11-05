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
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Password of the administrator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name     = $input->getOption('name');
        $password = $input->getOption('password');

        if (empty($name)) {
            throw new \InvalidArgumentException('Name should not be empty');
        }
        if (empty($password)) {
            throw new \InvalidArgumentException('Password should not be empty');
        }

        /** @var \Sententiaregum\Bundle\UserBundle\Entity\UserRepository $userRepository */
        $userRepository = $this->getContainer()->get('sen.user.repository');
        /** @var \Sententiaregum\Bundle\UserBundle\Util\PasswordHasher $passwordHasher */
        $passwordHasher = $this->getContainer()->get('sen.util.hasher');

        if (null !== $userRepository->findByName($name)) {
            throw new ExistingUserException(sprintf('User %s already in use', $name));
        }

        $entity = $userRepository->create($name, $passwordHasher->create($password), null, new \DateTime());
        $entity->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $userRepository->add($entity);
        $userRepository->attachRolesOnUser($entity->getRoles(), $userRepository->findByName($entity->getUsername())->getId());

        $output->writeln('<fg=green>New admin user with name "<fg=cyan>' . $name . '</fg=cyan>" added</fg=green>');
    }
}
