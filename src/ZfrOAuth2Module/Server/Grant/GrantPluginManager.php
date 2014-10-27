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

namespace ZfrOAuth2Module\Server\Grant;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;
use ZfrOAuth2\Server\Grant\GrantInterface;
use ZfrOAuth2Module\Server\Exception\RuntimeException;

/**
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @licence MIT
 */
class GrantPluginManager extends AbstractPluginManager
{
    /**
     * @var array
     */
    protected $factories = [
        'ZfrOAuth2\Server\Grant\AuthorizationGrant'     => 'ZfrOAuth2Module\Server\Factory\AuthorizationGrantFactory',
        'ZfrOAuth2\Server\Grant\ClientCredentialsGrant' => 'ZfrOAuth2Module\Server\Factory\ClientCredentialsGrantFactory',
        'ZfrOAuth2\Server\Grant\PasswordGrant'          => 'ZfrOAuth2Module\Server\Factory\PasswordGrantFactory',
        'ZfrOAuth2\Server\Grant\RefreshTokenGrant'      => 'ZfrOAuth2Module\Server\Factory\RefreshTokenGrantFactory',
    ];

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof GrantInterface) {
            return; // we're okay
        }

        throw new RuntimeException(sprintf(
            'Grant must implement "ZfrOAuth2\Server\Grant\GrantInterface", "%s" given',
            is_object($plugin) ? get_class($plugin) : gettype($plugin)
        ));
    }

    /**
     * {@inheritDoc}
     */
    protected function canonicalizeName($name)
    {
        return $name;
    }
}
