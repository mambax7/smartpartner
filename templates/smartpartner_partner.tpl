<{include file='db:smartpartner_header.tpl'}>

<span class="smartpartner_infotitle"><{$lang_partnerstitle}></span>

<div>
    <div class="smartpartner_partnertitle">
        <{if $partner.update_status == 'new'}>
            <img src='<{$smartPartner_url}>assets/images/icon/new_icon.gif'/>
        <{elseif $partner.update_status == 'updated'}>
            <img src='<{$smartPartner_url}>assets/images/icon/updated_icon.gif'/>
        <{/if}> <{$partner.urllink}>
        <img style='float: right; padding: 10px;' src='<{$partner.image}>' alt='<{$partner.clean_title}>' title='<{$partner.clean_title}>' align='right'
             border='5px'/></a><{$partner.title}> <{if $isAdmin}>
            <a href="<{$smartPartner_url}>admin/partner.php?op=mod&id=<{$partner.id}>"><img src="<{xoModuleIcons16 edit.png}>" title="<{$lang_edit}>" alt="<{$lang_edit}>"/></a>
            <a href="<{$smartPartner_url}>admin/partner.php?op=del&id=<{$partner.id}>"><img src="<{xoModuleIcons16 delete.png}>" title="<{$lang_delete}>" alt="<{$lang_delete}>"/></a>
        <{/if}>
    </div>
    <{if $partner.showsummary}>
        <div class="smartpartner_partnersummary"><{$partner.summary}></div>
    <{/if}> <{if $partner.display_type == 'full'}> <{if $offers}>
        <table>
            <tr>
                <th colspan='2'><b><{$lang_offers}></b></th>
            </tr>
            <{foreach item=offer from=$offers}>
                <tr>
                    <td width="60px">
                        <img src="<{$xoops_url}>/uploads/smartpartner/offer/<{$offer.image}>">
                    </td>
                    <td align="left">
                        <b><{$offer.title}></b><br> <{$offer.description}><br> <{if $offer.url}>
                            <a href='<{$offer.url}>' target='_blank'><{$lang_offer_click_here}></a>
                            <br>
                        <{/if}>
                    </td>
                </tr>
            <{/foreach}>
            <tr>
                <th colspan='2'></th>
            </tr>
        </table>
        <br>
        <br>
    <{/if}>
        <div class="smartpartner_partnersummary">
            <{$partner.description}>
        </div>
    <{/if}>
</div>

<br><{if $partner.display_type == 'full' && ($partner.contact_name || $partner.contact_email || $partner.contact_phone || $partner.adress || $partner.url)}>
<table width="100%">
    <tr>
        <th colspan="2"><b><{$lang_partner_informations}></b></th>
    </tr>
    <{if $partner.contact_name}>
        <tr>
            <td class="even" width="80px">
                <div style="font-weight: bold; text-align: center;"><{$lang_contact}></div>
            </td>
            <td class="odd"><{$partner.contact_name}></td>
        </tr>
    <{/if}> <{if $partner.contact_email && ($partner.email_priv == 0 || $isAdmin)}>
        <tr>
            <td class="even" width="80px">
                <div style="font-weight: bold; text-align: center;"><{$lang_email}></div>
            </td>
            <td class="odd"><{$partner.contact_email}></td>
        </tr>
    <{else}><!--<tr>
        <td class="even" width="80px"><div style="font-weight: bold; text-align: center;"><{$lang_email}></div></td>
        <td class="odd"><{$lang_private}></td>
      </tr> --><{/if}> <{if $partner.contact_phone && ($partner.phone_priv == 0 || $isAdmin)}>
        <tr>
            <td class="even" width="80px">
                <div style="font-weight: bold; text-align: center;"><{$lang_phone}></div>
            </td>
            <td class="odd"><{$partner.contact_phone}></td>
        </tr>
    <{else}><!-- <tr>
        <td class="even" width="80px"><div style="font-weight: bold; text-align: center;"><{$lang_phone}></div></td>
        <td class="odd"><{$lang_private}></td>
      </tr> --><{/if}> <{if $partner.adress && ($partner.adress_priv == 0 || $isAdmin)}>
        <tr>
            <td class="even" width="80px" valign="top">
                <div style="font-weight: bold; text-align: center;"><{$lang_adress}></div>
            </td>
            <td class="odd"><{$partner.adress}></td>
        </tr>
    <{else}><!--<tr>
        <td class="even" width="80px" valign="top"><div style="font-weight: bold; text-align: center;"><{$lang_adress}></div></td>
        <td class="odd"><{$lang_private}></td>
      </tr> --><{/if}> <{if $partner.url}>
    <tr>
        <td class="even" width="80px">
            <div style="font-weight: bold; text-align: center;"><{$lang_website}></div>
        </td>
        <td class="odd">
            <a href="vpartner.php?id=<{$partner.id}>" target="_blank"><{$partner.url}></a>
        </td>
    </tr>
    <{/if}></td></tr>
</table><{else}>
<div><b><{$partview_msg}></b></div><{/if}>

<{if $show_stats_block}>
    <br>
    <br>
    <div class="item" ;
    <div class="itemHead"><b><{$lang_stats}></b></div>
    <div class="itemInfo"><{$lang_page_been_seen}> <{$partner.hits_page}>
        <b><{$lang_times}></b></div>
    <div class="itemInfo"><{$lang_url_been_visited}> <{$partner.hits}>
        <b><{$lang_times}></b></div>
    </div><{/if}><{if $partner.files}>
    <table border="0" width="90%" cellspacing="1" cellpadding="0" align="center" class="outer">
        <tr>
            <td colspan="4" class="itemHead"><b><{$lang_files_linked}></b></td>
        </tr>
        <tr class="even">
            <td align="left" class="itemTitle"><b>File</b></td>
            <td align="center" width="100px" class="partnerTitle"><b>Date</b></td>
            <td align="center" width="50px" class="partnerTitle"><b>Hits</b></td>
        </tr>

        <!-- BEGIN DYNAMIC BLOCK --> <{foreach item=file from=$partner.files}>
            <tr>
                <td valign="middle" class="odd" align="left">
                    <a href="<{$xoops_url}>/modules/smartpartner/visit.php?fileid=<{$file.fileid}>" target="_blank"><img src="<{$smartsection_url}>assets/images/icon/file.gif"
                                                                                                                         title="<{$lang_download_file}>"
                                                                                                                         alt="<{$lang_download_file}>"/>&nbsp;<b><{$file.name}></b></a>

                    <div><{$file.description}></div>
                <td valign="middle" class="odd" align="center"><{$file.datesub}></td>
                <td valign="middle" class="odd" align="center"><{$file.hits}></td>
            </tr>
        <{/foreach}> <!-- END DYNAMIC BLOCK -->
        <tr></tr>
    </table>
    <br>
<{/if}>

<{include file='db:smartpartner_footer.tpl'}>
