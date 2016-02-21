<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/12/16
 * Time: 11:15 AM
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Author;
use Symfony\Component\Form\Exception\RuntimeException;

class CreateAdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:create')
            ->setDescription('Create blog administrator')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Admin name'
            )
            ->addArgument(
                'password',
                InputArgument::OPTIONAL,
                'Admin password'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $authors = $em->getRepository('AppBundle:Author')
            ->findAll();
        if ($authors) {
            foreach ($authors as $user) {
                if ($user->getRole() == 'ROLE_ADMIN') {
                    throw new \RuntimeException('Admin already exists!');
                }
            }
        }
        $name = $input->getArgument('name');
        $plainPassword = $input->getArgument('password');
        if ($plainPassword == "") {
            throw new \RuntimeException('Password must not be blank!'); }
        $userExists = $em->getRepository('AppBundle:Author')
            ->findOneBy(array('username' => $name));
        if ($userExists) {
            throw new \RuntimeException('User already exists: '.$name);
        }
        $admin = new Author();
        $admin->setDateTime(new \DateTime());
        $admin->setRole('ROLE_ADMIN');
        $admin->setUsername($name);
        $admin->setPlainPassword($plainPassword);
        $password = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($admin, $admin->getPlainPassword());
        $admin->setPassword($password);
        $em->persist($admin);
        $em->flush();
        $output->writeln('Admin created!');
    }

}