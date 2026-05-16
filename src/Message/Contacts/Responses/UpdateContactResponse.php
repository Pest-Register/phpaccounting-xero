<?php

namespace PHPAccounting\Xero\Message\Contacts\Responses;

/**
 * Update Contact(s) Response.
 *
 * Xero's update endpoint returns the same Contact shape as create, so the
 * parsing is identical to CreateContactResponse. The dedicated class exists
 * for symmetry with the other entities (Invoices/Accounts/etc. each have
 * their own Update*Response) and so callers can type-hint the update path
 * separately from create.
 *
 * @package PHPAccounting\Xero\Message\Contacts\Responses
 */
class UpdateContactResponse extends CreateContactResponse
{
}
