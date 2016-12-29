<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Panel\Listing;

/**
 * Interface AppointmentItem
 * @package ILIAS\UI\Component\Panel\Listing
 */
interface AppointmentItem extends \ILIAS\UI\Component\Component {
	/**
	 * Gets the title of the appointment item
	 *
	 * @return string
	 */
	public function getTitle();


	/***
	 * Get the starting point of the appointment.
	 * @return ilDateTime
	 */
	public function getFrom();

	/***
	 * Get the ending point of the appointment.
	 * @return ilDateTime
	 */
	public function getEnd();

	/***
	 * Get the color of the calendar containing the item as color code (hex).
	 * @return string
	 */
	public function getColor();

	/**
	 * Creates a new appointment item with a description.
	 * @param string $description
	 * @return AppointmentItem
	 */
	public function withDescription($description);

	/**
	 * Get the description of the appointment.
	 * @return string
	 */
	public function getDescription();

	/**
	 * Set of meta data as key-value pairs. The key is holding the title and the
	 * value is holding the content of the specific data set.
	 * @param [] $meta_data string (Title) => string (Content)
	 * @return AppointmentItem
	 */
	public function withMetaData($meta_data);

	/**
	 * Get the meta data of the appointment.
	 * @return string[] string (Title) => string (Content)
	 */
	public function getMetaData();

	/**
	 * Creates a new appointment item with a set of actions to perform on it.
	 * Those actions will be listed in the dropdown on the right side of the
	 * appointement.
	 * @param string[] $actions
	 * @return AppointmentItem
	 */
	public function withActions($actions);

	/**
	 * Gets the actions of the appointment.
	 * @return string[]
	 */
	public function getActions();
}
