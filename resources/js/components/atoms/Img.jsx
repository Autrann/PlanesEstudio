
function Img({
    params,
    className,
    imgClassName,
    children
}) {

    let finalSrc = '';
    let url =`/images/icons/:icon.webp`;
    params && Object.keys(params).forEach((key) => {
        const placeholder = `:${key}`
        if (url.includes(placeholder)) {
            url = url.replace(placeholder, params[key]);
        }
    })
    finalSrc = `${url}`;

    return (
        <div className={`${className || ""}`}>
            <img className={`${imgClassName || ""}`} src={`${finalSrc}`} alt={`icon`} />
            {children}
        </div>
    )

}

export default Img;