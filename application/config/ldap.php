<?php
$config['binduser'] = 'cn=api,ou=Services,dc=ops,dc=api-base,dc=com';
$config['basedn'] = 'dc=ops,dc=api-base,dc=com';
$config['bindpw'] = '8CO7Tckp';
/*
 * The host name parameter can be a space separated list of host names.
 * This means that the LDAP code will talk to a backup server if the main server is not operational.
 * There will be a delay while the code times out trying to talk to the main server but things will still work.
*/
$config['server'] = 'ldap1 ldap2';
$config['port'] = NULL;
/*
 * Controls the LDAP_OPT_NETWORK_TIMEOUT option, this is how long the code will attempt to talk to the primary server if it is unreachable.
 */
$config['timeout'] = 5;
?>
