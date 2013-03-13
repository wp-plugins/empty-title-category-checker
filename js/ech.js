jQuery(document).ready(function()
{
    jQuery("input#publish").click(function()
    {
		var categories = new Array(),
		title='';

        var empty = false;

/* check title */
		title = jQuery("#title").val();
		if (title == '')
		{
			alert(cpa_l10n_obj.title_is_empty);
			empty=true;
		}

/* check category */

		if (!empty && cpa_l10n_obj.post_type == 'post')
		{
			empty = true;
			jQuery("#categorychecklist input:checkbox:checked").each(function(index)
			{
				categories[index] = jQuery(this).val();
			});


			for(i=0; i<categories.length; i++)
			{
				if (categories[i] != 1) // without category
				{
					empty=false;
					break;
				}
			}

	   	    if (empty) // no category is checked
				alert (cpa_l10n_obj.category_is_empty);
		}

        if (empty)
        {
            jQuery("#submitpost .spinner").hide();
            jQuery("input#publish").removeClass("button-primary-disabled");
        }

        return !empty;
    });
});