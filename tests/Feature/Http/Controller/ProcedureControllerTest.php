<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller;

use BombenProdukt\JsonRpc\Test\ProcedureCaller;

// These tests are based on the examples from https://www.jsonrpc.org/specification

it('rpc call with positional parameters', function (): void {
    ProcedureCaller::call('rpc-call-with-positional-parameters-1');
    ProcedureCaller::call('rpc-call-with-positional-parameters-2');
});

it('rpc call with named parameters', function (): void {
    ProcedureCaller::call('rpc-call-with-named-parameters-1');
    ProcedureCaller::call('rpc-call-with-named-parameters-2');
});

it('rpc call with a Notification', function (): void {
    ProcedureCaller::call('rpc-call-with-a-notification');
});

it('rpc call of non-existent method', function (): void {
    ProcedureCaller::call('rpc-call-of-non-existent-method');
});

it('rpc call with invalid JSON', function (): void {
    ProcedureCaller::call('rpc-call-with-invalid-json');
});

it('rpc call with invalid Request object', function (): void {
    ProcedureCaller::call('rpc-call-with-invalid-request-object');
});

it('rpc call Batch, invalid JSON', function (): void {
    ProcedureCaller::call('rpc-call-batch-invalid-json');
});

it('rpc call with an empty Array', function (): void {
    ProcedureCaller::call('rpc-call-with-an-empty-array');
});

it('rpc call with an invalid Batch (but not empty)', function (): void {
    ProcedureCaller::call('rpc-call-with-an-invalid-batch-but-not-empty');
});

it('rpc call with invalid Batch', function (): void {
    ProcedureCaller::call('rpc-call-with-invalid-batch');
});

it('rpc call Batch', function (): void {
    ProcedureCaller::call('rpc-call-batch');
});

it('rpc call Batch (all notifications)', function (): void {
    ProcedureCaller::call('rpc-call-batch-all-notifications');
});
