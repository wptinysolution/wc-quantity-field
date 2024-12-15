import React from "react";

function FieldWrapper(props){
    const { children, label, desc, type } = props
    return (
        <div className={`field-wrapper flex justify-between text-left mb-3 ${ type || '' }`}>
            <div className='field-label-wrapper w-2/5 mt-3 items-center text-base font-medium' >
                { label || 'Label' }
            </div>
            <div className='field-wrapper-child rounded p-4 w-3/5 text-left border border-slate-200'>
                {children}
                {
                    desc ? <p className='mt-2' > {desc}  </p> : null
                }
            </div>
        </div>
    );
}

export default FieldWrapper;