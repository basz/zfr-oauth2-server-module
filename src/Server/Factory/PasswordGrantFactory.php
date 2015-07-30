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

namespace ZfrOAuth2Module\Server\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfrOAuth2\Server\Grant\PasswordGrant;

/**
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @licence MIT
 */
class PasswordGrantFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        /* @var \ZfrOAuth2Module\Server\Options\ModuleOptions $options */
        $options = $parentLocator->get('ZfrOAuth2Module\Server\Options\ModuleOptions');

        $ownerCallable = $options->getOwnerCallable();
        $ownerCallable = is_string($ownerCallable) ? $parentLocator->get($ownerCallable) : $ownerCallable;

        /* @var \ZfrOAuth2\Server\Service\TokenService $accessTokenService */
        $accessTokenService = $parentLocator->get('ZfrOAuth2\Server\Service\AccessTokenService');

        /* @var \ZfrOAuth2\Server\Service\TokenService $refreshTokenService */
        $refreshTokenService = $parentLocator->get('ZfrOAuth2\Server\Service\RefreshTokenService');

        return new PasswordGrant($accessTokenService, $refreshTokenService, $ownerCallable);
    }
}
