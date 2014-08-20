function validateLength(value, minLength, errorRequiredElement, errorMinLengthElement)
{
    if($.trim(value) == "" || value.length == 0)
    {
        hideElement(errorMinLengthElement);
        showElement(errorRequiredElement);
        return 1;
    }
    else
    {
        hideElement(errorRequiredElement);
    }
    if(value.length < minLength)
    {
        showElement(errorMinLengthElement);
        return 1;
    }
    else
    {
        hideElement(errorMinLengthElement);
    }

    return 0;
}