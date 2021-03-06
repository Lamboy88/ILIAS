<?php

/* Copyright (c) 2019 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\Setup;

use ILIAS\UI;

/**
 * An admin needs to confirm something to achieve this objective.
 */
class AdminConfirmedObjective implements Objective
{
    /**
     * @var string
     */
    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function getHash() : string
    {
        return hash(
            "sha256",
            get_class($this) . "::" . $this->message
        );
    }

    /**
     * @inheritdoc
     */
    public function getLabel() : string
    {
        return "Get a confirmation from admin.";
    }

    /**
     * @inheritdoc
     */
    public function isNotable() : bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getPreconditions(Environment $environment) : array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function achieve(Environment $environment) : Environment
    {
        $admin_interaction = $environment->getResource(Environment::RESOURCE_ADMIN_INTERACTION);
        $achievement_tracker = $environment->getResource(Environment::RESOURCE_ACHIEVEMENT_TRACKER);

        if ($achievement_tracker->isAchieved($this)) {
            return $environment;
        }

        if (!$admin_interaction->confirmOrDeny($this->message)) {
            throw new NoConfirmationException(
                $this->message
            );
        }

        $achievement_tracker->trackAchievementOf($this);

        return $environment;
    }
}
