import { useCallback, useRef, useState, useEffect } from "react";
import apiCatalog from "./apiCatalog";
import axios from 'axios';

/**
 * Custom hook for HTTP requests using axios and apiCatalog.
 * Supports pathParams, queryParams, and request body (data).
 */
const useFetch = (
    method = "get",
    endPoint,
    immediate = true,
    params = {}
) => {
    const [loading, setLoading] = useState(false);
    const [request, setRequest] = useState({
        method,
        pathParams: params.pathParams,
        queryParams: params.queryParams,
        data: params.data,
    });

    const responseData = useRef({
        data: null,
        error: null,
    });

    const controller = new AbortController();
    const baseUrl = import.meta.env.VITE_API_BACK;
    const urlTemplate = apiCatalog[endPoint]?.url || "";

    const fetchData = useCallback(async () => {
        setLoading(true);
        try {
            // Build the URL with any path parameters
            let tempUrl = urlTemplate;
            if (request.pathParams) {
                Object.entries(request.pathParams).forEach(([key, value]) => {
                    tempUrl = tempUrl.replace(`:${key}`, value);
                });
            }
            const fullUrl = `${baseUrl}${tempUrl}`;

            // Configure axios options
            const axiosConfig = {
                method: request.method,
                url: fullUrl,
                signal: controller.signal,
                // Attach query parameters if provided
                ...(request.queryParams && { params: request.queryParams }),
                // Attach request body for POST/PUT if provided
                ...(request.data && { data: request.data }),
            };

            const response = await axios(axiosConfig);
            responseData.current = {
                data: response.data,
                error: null,
            };
        } catch (err) {
            if (axios.isCancel(err)) {
                console.log("Request canceled", err.message);
            } else {
                responseData.current = {
                    data: null,
                    error: err,
                };
            }
        } finally {
            setLoading(false);
        }
    }, [request, urlTemplate]);

    useEffect(() => {
        if (immediate) {
            fetchData();
        }
        return () => controller.abort();
    }, [immediate, fetchData]);

    /**
     * Execute or re-execute the request with optional overrides.
     * @param {string} newMethod - HTTP method override
     * @param {{ pathParams?: object, queryParams?: object, data?: any }} newParams
     */
    const execute = async (newMethod, newParams = {}) => {
        setRequest(prev => ({
            method: newMethod || prev.method,
            pathParams: newParams.pathParams || prev.pathParams,
            queryParams: newParams.queryParams || prev.queryParams,
            data: newParams.data || prev.data,
        }));
        await fetchData();
    };

    return {
        data: responseData.current.data,
        loading,
        error: responseData.current.error,
        execute,
    };
};

export default useFetch;
