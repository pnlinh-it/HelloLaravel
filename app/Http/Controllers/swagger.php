<?php

/**
 * @OA\Info(
 *         version="1.0.0",
 *         title="Invoice Ninja APIsădaw",
 *         description="An open-source invoicing and time-tracking app built with Laravel",
 *         termsOfService="",
 *         @OA\Contact(
 *             email="contact@invoiceninja.com"
 *         ),
 *         @OA\License(
 *             name="Attribution Assurance License",
 *             url="https://raw.githubusercontent.com/invoiceninja/invoiceninja/master/LICENSE"
 *         )
 *  )
 */

/**
 * @OA\Get(
 *   path="/clients/{client_id}",
 *   summary="Retrieve a client",
 *   operationId="getClient",
 *   tags={"client"},
 *   @OA\Parameter(
 *     in="query",
 *     name="client_id",
 *     required=true,
 *     @OA\Schema(
 *       type="integer"
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="A single client",
 *      @OA\Schema(type="object")
 *   ),
 *   @OA\Response(
 *     response="default",
 *     description="an ""unexpected"" error"
 *   )
 * )
 */
