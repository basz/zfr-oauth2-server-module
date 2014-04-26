<?php
/*
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
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfrOAuth2Module\Server\Authentication\Storage;

use Zend\Authentication\Storage\NonPersistent;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Request;
use Zend\Mvc\Application;
use Zend\Mvc\ApplicationInterface;
use ZfrOAuth2\Server\ResourceServer;

/**
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @licence MIT
 */
class AccessTokenStorage extends NonPersistent
{
    /**
     * @var ResourceServer
     */
    protected $resourceServer;

    /**
     * @var Application
     */
    private $application;

    /**
     * @param ResourceServer $resourceServer
     * @param Application    $application
     */
    public function __construct(ResourceServer $resourceServer, Application $application)
    {
        $this->resourceServer = $resourceServer;
        $this->application    = $application;
    }

    /**
     * Set the HTTP request
     *
     * @param  HttpRequest $request
     * @return void
     */
    public function setRequest(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        $request = $this->getCurrentRequest();

        return $request ? $this->resourceServer->getAccessToken($request) === null : true;
    }

    /**
     * {@inheritDoc}
     */
    public function read()
    {
        $request = $this->getCurrentRequest();

        if (! $request) {
            return null;
        }

        $accessToken = $this->resourceServer->getAccessToken($request);

        return $accessToken ? $accessToken->getOwner() : null;
    }

    /**
     * @return Request|null
     */
    private function getCurrentRequest()
    {
        $request = $this->application->getMvcEvent()->getRequest();

        return $request instanceof Request ? $request : null;
    }
}
