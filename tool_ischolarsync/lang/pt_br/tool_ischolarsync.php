<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file for tool_ischolarsync, pt-BR
 *
 * File         tool_ischolarsync.php
 * Encoding     UTF-8
 *
 * @package   tool_ischolarsync
 * @copyright 2021, iScholar - Gestão Escolar
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Sincronização iScholar <> Moodle';

$string['ischolarsettings'] = 'Sincronização iScholar <> Moodle';

$string['settings:enabled'] = 'Ativado:';
$string['settings:enabledinfo'] = 'Ativa / desativa este plugin.';
$string['settings:tokenischolar'] = 'Token do iScholar:';
$string['settings:tokenischolarinfo'] = 'Token de acesso de seu sistema iScholar. Clique <a href="https://ajuda.ischolar.com.br/pt-BR/articles/5668326-sincronizacao-ischolar-moodle" target="_blank">aqui</a> para saber mais.';
$string['settings:healthcheck'] = 'Status de configuração:';
$string['settings:initialsetupinfo'] = '';
$string['settings:userlastname'] = 'Integrações';
$string['settings:userdescription'] = '<h3 dir="ltr" style="text-align: left;"><strong>NÃO ALTERE E NÃO REMOVA ESTE USUÁRIO!</strong></h3><p dir="ltr" style="text-align: left;">A alteração ou remoção deste usuário acarretará no mal funcionamento de plugins <em>iScholar</em>.</p>';

$string['config:pluginenabled'] = 'Ativação do plugin.';
$string['config:webservice'] = 'Permissão do Moodle para conexão com sistemas externos.';
$string['config:webserviceprotocols'] = 'Protocolo de comunicação.';
$string['config:createuser'] = 'Usuário de integração.';
$string['config:usercapability'] = 'Permissões do usuário de integração.';
$string['config:selectservice'] = 'Serviço iScholar.';
$string['config:servicefunctions'] = 'Funções do serviço iScholar.';
$string['config:serviceuser'] = 'Usuário do serviço.';
$string['config:createtoken'] = 'Token de acesso do Moodle.';
$string['config:webservicedocs'] = 'Configuração de documentações no Moodle.';
$string['config:servicetest'] = 'Configuração no sistema iScholar.';
$string['config:servicetestfail'] = 'Falha na comunicação com o iScholar.';
$string['config:timezone'] = 'Fuso horário.';
$string['config:manageauth'] = 'Ativação do método de autenticação.';
$string['config:customfields'] = 'Campos customizados.';
$string['config:plugindisabled'] = 'Plugin desativado.';
$string['config:exception'] = 'Exceção.';

$string['configerror:general'] = 'Falha na comunicação com o iScholar:';
$string['configerror:communication'] = 'O iScholar não conseguiu estabelecer comunicação com o Moodle.';
$string['configerror:timezone'] = 'Verifique e configure o fuso horário como descrito nesta <a target="_blank" href="https://ischolar.com.br">página de ajuda</a>.';
$string['configerror:tokeninvalido'] = 'O Token do iScholar fornecido é inválido para esta aplicação.';
$string['configerror:tokenexpirado'] = 'O Token do iScholar fornecido expirou. Acesse seu iScholar para gerar um novo token.';
$string['configerror:escoladesconhecida'] = 'A escola associada ao Token do iScholar é desconhecida.';
$string['configerror:integracaodesconhecida'] = 'A integração associada ao Token do iScholar fornecido é desconhecida.';
$string['configerror:tokennaoencontrado'] = 'Token do iScholar não encontrado.';
$string['configerror:fixbutton'] = 'Corrigir configurações';

$string['customfield:ischolar_aluno'] = 'Cód. do aluno';
$string['customfield:ischolar_professor'] = 'Cód. do professor';
$string['customfield:ischolar_disciplina'] = 'Cód. da disciplina';
$string['customfield:ischolar_curso'] = 'Cód. do curso';
$string['customfield:ischolar_modalidade'] = 'Cód. da modalidade';
