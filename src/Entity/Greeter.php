<?php

namespace App\Entity;

class Greeter
{
    private array $greetings = [
        "Welcome to our website!",
        "Hello there! Have a great day!",
        "Greetings! Enjoy your stay!",
        "Hi! We're glad you're here!",
        "Welcome aboard!"
    ];

    public function getGreeting(): string {
        return $this->greetings[array_rand($this->greetings)];
    }
}