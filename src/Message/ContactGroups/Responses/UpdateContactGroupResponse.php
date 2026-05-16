<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Responses;

/**
 * Update Contact Group(s) Response.
 *
 * Xero's update endpoint returns the same ContactGroup shape as create.
 * See UpdateContactResponse for the rationale on having a dedicated class.
 *
 * @package PHPAccounting\Xero\Message\ContactGroups\Responses
 */
class UpdateContactGroupResponse extends CreateContactGroupResponse
{
}
