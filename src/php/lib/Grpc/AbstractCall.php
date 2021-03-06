<?php
/*
 *
 * Copyright 2015, Google Inc.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *     * Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above
 * copyright notice, this list of conditions and the following disclaimer
 * in the documentation and/or other materials provided with the
 * distribution.
 *     * Neither the name of Google Inc. nor the names of its
 * contributors may be used to endorse or promote products derived from
 * this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */
namespace Grpc;

abstract class AbstractCall {

  protected $call;
  protected $deserialize;
  protected $metadata;

  /**
   * Create a new Call wrapper object.
   * @param Channel $channel The channel to communicate on
   * @param string $method The method to call on the remote server
   */
  public function __construct(Channel $channel, $method, $deserialize) {
    $this->call = new Call($channel, $method, Timeval::infFuture());
    $this->deserialize = $deserialize;
    $this->metadata = null;
  }

  /**
   * @return The metadata sent by the server.
   */
  public function getMetadata() {
    return $this->metadata;
  }

  /**
   * Cancels the call
   */
  public function cancel() {
    $this->call->cancel();
  }

  /**
   * Deserialize a response value to an object.
   * @param string $value The binary value to deserialize
   * @return The deserialized value
   */
  protected function deserializeResponse($value) {
    if ($value === null) {
      return null;
    }
    return call_user_func($this->deserialize, $value);
  }
}