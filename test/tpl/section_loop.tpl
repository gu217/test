<table>
<tr>
{section name=list loop=$loop}
<td>{$loop[list].title}</td>
{if $smarty.section.loop.index%5==0}
</tr><tr>
{/if}
{/section}
</tr>
</table>
