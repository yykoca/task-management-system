<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setName("admin");
        $admin->setEmail("admin@admin");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword("$2y$13\$ZxF69.8wt5Qms1EDDi5nhupPvb52dsFRJXqWJUFk1vylxzor4Ioq2");
        $manager->persist($admin);

        $user = new User();
        $user->setName("user");
        $user->setEmail("user@user");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword("$2y$13\$ZxF69.8wt5Qms1EDDi5nhupPvb52dsFRJXqWJUFk1vylxzor4Ioq2");
        $manager->persist($user);

        $tasks = [
            ["title" => "Prepare quarterly report", "description" => "Compile financial data and generate a detailed report for the last quarter's performance."],
            ["title" => "Update website content", "description" => "Review existing content, make necessary revisions, and upload new information to the company's website."],
            ["title" => "Organize team meeting", "description" => "Schedule a team meeting to discuss project updates, address any concerns, and plan upcoming tasks."],
            ["title" => "Research market trends", "description" => "Conduct thorough market research to identify current trends and gather insights for strategic planning."],
            ["title" => "Resolve customer complaints", "description" => "Investigate customer complaints, provide timely responses, and implement solutions to enhance customer satisfaction."],
        ];

        foreach ($tasks as $taskData) {
            $task = new Task();
            $task->setTitle($taskData['title']);
            $task->setDescription($taskData['description']);
            $manager->persist($task);
        }

        $manager->flush();
    }
}
