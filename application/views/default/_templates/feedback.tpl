{section name=f loop=$feedbackPositive}
    <div class="feedback success">{$feedbackPositive[f]}</div>
{/section}
{section name=f loop=$feedbackNegative}
    <div class="feedback error">{$feedbackNegative[f]}</div>
{/section}